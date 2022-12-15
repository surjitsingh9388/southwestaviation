<?php 
$sessionUser = $this->request->session()->read('Auth.User');
$sessionArray = $this->Session->read('Auth.User'); 
use Cake\Routing\Router;

$hlogDate = !empty($params['log_date']) ? $params['log_date'] : '';
$hHours = !empty($params['hours']) ? $params['hours'] : '';
$hCycles = !empty($params['cycles']) ? $params['cycles'] : '';
//Component details display in popup
$tHtml = '<div class="x_panel"><div class="x_content">
            <input type="hidden" name="plane_id" value="'.$airCompParts['id'].'">
            <input type="hidden" class="partIdCls" name="partId">
            <input type="hidden" name="partids" value="'.$params['partids'].'">';
            if(!empty($params['type']) && $params['type'] == 'all') {
                $tHtml .= '<input type="hidden" name="type" value="all">';
            }
$tHtml .='<div class="row">
            <div class="col-md-12" style="padding:0 15px 25px 15px;">
                <label>Date</label>
                <input type="text" name="log_date" value="'.$hlogDate.'" class="form-control logDatepicker">
                <input type="hidden" name="hlog_date" value="'.$hlogDate.'">
            </div> 
        </div>
        <div class="row">
            <div class="col-md-12" style="padding:0 15px 25px 15px;">
                <label>Hours</label>
                <input type="text" name="hours" value="'.$hHours.'" class="form-control">
                <input type="hidden" name="hhours" value="'.$hHours.'">
            </div> 
        </div>
        <div class="row">
            <div class="col-md-12" style="padding:0 15px 25px 15px;">
                <label>Landing/Cycles</label>
                <input type="number" name="cycles" value="'.$hCycles.'" class="form-control">
                <input type="hidden" name="hcycles" value="'.$hCycles.'">
            </div> 
        </div>';
$tHtml .= '</div></div><span class="getselectedobj"></span>';
?>

<div class="content sliding">
    <div class="outerWrapper">
        
        <style type="text/css">
            .myAlert-top{
                position: fixed;
                top: 65px; 
                left:2%;
                width: 96%;
                display: none;
                z-index: 12;
                font-weight: bold;
                font-size: 18px;
            }
        </style>
        <div class="myAlert-top alert alert-danger">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            The compliance date can not be blank or in the future!.
        </div>

        <div id="myCarousel" class="carousel" data-interval="false" data-ride="carousel">
            <div class="carousel-inner">
                <?php
                $partCount = count($partRecords);
                $i=1;
                foreach ($partRecords as $airCompParts) {
                    $active = '';
                    if($i <= 1) {
                        $active = 'active';
                    }
                ?>
                    <div class="item <?php echo $active; ?>">
                        <div class="btnWrapper">
                            <?php 
                            if($partCount > 1) {
                            ?>
                            <h2 class="heading partInfoHeading">
                            Add Compliance <?php echo $i." of ".$partCount." - ".$airCompParts['plane']['plane_code']." #".$airCompParts['id'].", ".$airCompParts['ata_code']." ".$airCompParts['mfg_code']; ?>
                            <span class="glyphicon glyphicon-triangle-bottom partSelectorBtn"></span>
                            <?php
                            } else {
                            ?>
                            <h2 class="heading">
                            Maintenance Items / <?php echo $airCompParts['plane']['plane_code']." #".$airCompParts['id'].", ".$airCompParts['ata_code']." ".$airCompParts['mfg_code']; ?> / Add Compliance
                            <?php
                            }
                            ?>
                            </h2>

                            <div class="btnWrap">
                                <?php
                                if((!empty($actionItems) && $actionItems['action']['action_add'] == 1) || $sessionUser['id'] == 1) {
                                    echo $this->Html->link("<i class='fa fa-mail-reply'></i> Go Back", 'javascript:history.back()', array('class' => 'btn btn-default', 'escape' => false));
                                    echo $this->Html->link("<i class='fa fa-mail-reply'></i> Maintenance Due List", 'javascript:history.go(-2)', array('class' => 'btn btn-default', 'escape' => false));                               
                                }
                                    echo $this->Form->button('Save', ['type' => 'button', 'class' => 'btn btn-default saveCompReport', 'data-type'=>!empty($params['type'])?$params['type']:'single', 'data-plane_id'=>$airCompParts['plane_id']]);
                                ?>
                            </div>
                        </div>

                        <div class="partDetailsPopup" style="display: none;">
                            <div class="modal-content">
                                <div class="partDetailPopupHeader">
                                    <span><?php echo $i." of ".$partCount." - ".$airCompParts['id']." - ".$airCompParts['description']; ?></span>
                                    <?php
                                    if($partCount > 1) {
                                    ?>    
                                    <a class="mvleft carousel-control" href="#myCarousel" data-slide="prev">
                                        <span class="glyphicon glyphicon-chevron-left"></span>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                    <a class="mvright carousel-control" href="#myCarousel" data-slide="next">
                                        <span class="glyphicon glyphicon-chevron-right"></span>
                                        <span class="sr-only">Next</span>
                                    </a>
                                    <?php } ?>
                                    <br>
                                    <button type="button" class="btn btn-default closePartInfo">Close</button>
                                    <!-- <button type="button" class="btn btn-danger removePartParent">Remove</button> -->
                                </div>

                                <div style="max-height: 340px; overflow-y: auto; width: 100%;">
                                    <table id="aircraftUtilization" class="table table-hover table-header-dark">
                                        <thead>
                                            <tr>
                                                <th width="5%"><input type="checkbox" name="part_info" class="partCheckAll" data-leavepid="<?php echo $airCompParts['id']; ?>"></th>
                                                <th width="5%">Item</th>
                                                <th width="5%">#</th>
                                                <th width="10%">Type</th>
                                                <th width="15%">ATA/MFG Code</th>
                                                <th width="60%">Description</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $ii=1;
                                        foreach ($partRecords as $key => $value) {
                                            if($ii != $i) {
                                                if($airCompParts['id'] != $value['id']) {
                                        ?>
                                            <tr>
                                                <td><input type="checkbox" class="partChkBoxCls" name="PartIds[]" value="<?php echo $value['id']; ?>"></td>
                                                <td><?php echo $ii; ?></td>
                                                <td><?php echo $value['id']; ?></td>
                                                <td><?php echo $value['airframe_component']['log_book']; ?></td>
                                                <td><?php echo $value['ata_code']." ".$value['mfg_code']; ?></td>
                                                <td><?php echo $value['description']; ?></td>
                                            </tr>            
                                        <?php
                                                }
                                            }
                                        $ii++;    
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="page-content mt-35">
                        <?php
                        echo $this->Form->create($airCompParts, array('class'=>'form-horizontal form-label-left errorCorrectForm'));
                        
                            if(!empty($params['type']) && $params['type'] == 'all') {
                                $airCompParts['airframe_component_last_cw'][0]['last_cw_date'] = $params['log_date'];
                                $airCompParts['airframe_component_last_cw'][0]['last_cw_hrs'] = $params['hours'];
                                $airCompParts['airframe_component_last_cw'][0]['last_cw_afl'] = $params['cycles'];
                            }

                            $override = !empty($airCompParts['airframe_component_last_cw'][0]['override']) ? $airCompParts['airframe_component_last_cw'][0]['override'] : '';
                            $eom = !empty($airCompParts['airframe_component_last_cw'][0]['eom']) ? $airCompParts['airframe_component_last_cw'][0]['eom'] : '';
                            
                            $lastCwDate = !empty($airCompParts['airframe_component_last_cw'][0]['last_cw_date']) ? $airCPComp->dateFormat($airCompParts['airframe_component_last_cw'][0]['last_cw_date']) : '';

                            $readonly = true;
                            if(!empty($airCompParts['airframe_component_last_cw'][0]['override'])) {
                                $readonly = false;
                            }

                            $date = !empty($airCompParts['airframe_component_last_cw'][0]['last_cw_date']) ? date('m-d-Y', strtotime($airCompParts['airframe_component_last_cw'][0]['last_cw_date'])) : '';

                            //Next due
                            if(!empty($ftype) && $ftype == 'initial') {
                                $lastCwDate = '';
                                $airCompParts['airframe_component_last_cw'][0]['last_cw_hrs'] = 0;
                                $airCompParts['airframe_component_last_cw'][0]['last_cw_afl'] = 0;
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

                            if(!empty($ftype) && $ftype == 'initial') {
                                $mos = strtoupper(date('m-d-Y', strtotime("-1 day")));
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

                            $style = '';
                            if ((!empty($mos) && strtotime($mos) <= strtotime(date('m-d-Y'))) || (empty($mos) && !empty($remHours) && $remHours < 0)) {
                                $style = 'pastDue';
                            }

                            $partType = !empty($airCompParts['part_installed_times'][0]['part_type']) ? $airCompParts['part_installed_times'][0]['part_type'] : '';
                            ?>
                            <input type="hidden" name="part_id" class="part_id" value="<?php echo $airCompParts['id']; ?>">
                            <input type="hidden" name="plane_id" class="plane_id" value="<?php echo $airCompParts['plane_id']; ?>">
                            <input type="hidden" name="airframe_component_id" class="airframe_component_id" value="<?php echo $airCompParts['airframe_component_id']; ?>">
                            <input type="hidden" name="compHours" class="compHours" value="<?php echo $compHours; ?>">
                            <input type="hidden" name="compCycles" class="compCycles" value="<?php echo $compCycles; ?>">
                            
                            <div class="formBGCls">
                                <div class="item-info addPartBorder">
                                    <div class="addPageHeading">Primary Information</div>
                                    <div class="row">
                                        <div class="form-group col-md-3">
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <label>Registration Number</label>
                                            </div>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <?php echo $airCompParts['plane']['plane_code']; ?>
                                            </div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <label>Item Type</label>
                                            </div>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <?php echo $airCompParts['item_type']; ?>
                                            </div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <div class="col-md-4 col-sm-4 col-xs-12">
                                                <label>ATA</label>
                                            </div>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                <?php echo $airCompParts['ata_code']; ?>
                                            </div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <label>Mfg Code</label>
                                            </div>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <?php echo $airCompParts['mfg_code']; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-md-3">
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <label>Component</label>
                                            </div>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <?php echo $airCompParts['airframe_component']['log_book']; ?>
                                            </div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <label>Disposition</label>
                                            </div>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <?php echo $airCompParts['disposition']; ?>
                                            </div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <div class="col-md-4 col-sm-4 col-xs-12">
                                                <label>Reference</label>
                                            </div>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                <?php echo $airCompParts['reference']; ?>
                                            </div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <label>Requirement Type</label>
                                            </div>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <?php echo $airCompParts['requirement_type']; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <div class="col-md-2 col-sm-2 col-xs-12">
                                                <label>Item Name</label>
                                            </div>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <?php echo $airCompParts['description']; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div style="clear: both;"></div>

                                <!-- Tabs Start -->
                                <div id="aircraftTabs" style="padding: 15px 0 15px 0;">
                                    <div class="container">
                                        <ul class="nav nav-tabs">
                                            <li class="active"><a data-toggle="tab" href="#itemInfo<?php echo $i; ?>">Item Information</a></li>
                                            <li><a data-toggle="tab" href="#subItems<?php echo $i; ?>">Additional Information</a></li>
                                        </ul>
                                        <div class="tab-content">
                                            <div id="itemInfo<?php echo $i; ?>" class="tab-pane fade in active">
                                                <div class="item-info">
                                                    <div class="corrPageHeading">
                                                        <div class="corrPH">Compliance</div>
                                                        <div class="corrPH" style="background: #518aed;">Next Due</div>
                                                        <div class="corrPH" style="background: #518aed;">Remaining</div>
                                                        <div class="corrPH">Interval Adjustments</div>
                                                        <div class="corrPH">Interval</div>
                                                    </div>
                                                    <div class="itemInfoCls">
                                                        <div class="itemCls">
                                                            <div class="form-group">
                                                                <div class="col-md-12 col-sm-12 col-xs-12">
                                                                    <a href="javascript:void(0)" style="cursor: pointer; color: #3c8dbc;" class="complienceTimeCls" data-part_id="<?= h($airCompParts['id']) ?>" data-plane_id="<?= h($airCompParts['plane_id']) ?>" data-comp_id="<?= h($airCompParts['airframe_component']['id']) ?>">Complience Times <i class="fa fa-clock-o"></i></a>
                                                                </div>
                                                            </div>

                                                            <div class="form-group"> 
                                                                <label class="col-md-4 col-sm-4 col-xs-12" for="last_cw_date">Date 
                                                                </label>
                                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                                    <div class="input-group date datePicker">
                                                                        <?php echo $this->Form->Text('last_cw_date', array('class'=>'form-control col-md-7 col-xs-12 cwDatepicker', 'label'=> false, 'value'=>$lastCwDate)); ?>
                                                                        <span class="input-group-addon">
                                                                            <span class="glyphicon glyphicon-calendar"></span>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="col-md-4 col-sm-4 col-xs-12" for="last_cw_hrs">Hours
                                                                </label>
                                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                                    <?php echo $this->Form->control('last_cw_hrs', array('class'=>'form-control col-md-7 col-xs-12 last-cw-hrs keypress', 'label'=>false, 'value'=>$airCompParts['airframe_component_last_cw'][0]['last_cw_hrs'])); ?>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="col-md-4 col-sm-4 col-xs-12" for="last_cw_afl">Cycles
                                                                </label>
                                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                                    <?php echo $this->Form->control('last_cw_afl', array('class'=>'form-control col-md-7 col-xs-12 last-cw-afl keypress', 'label'=>false, 'value'=>$airCompParts['airframe_component_last_cw'][0]['last_cw_afl'])); ?>
                                                                </div>
                                                            </div>                               
                                                        </div>

                                                        <div class="itemCls">
                                                            <div class="form-group">
                                                                <div class="col-md-7 col-sm-7 col-xs-12">
                                                                    <?php echo $this->Form->checkbox('override', array('label'=>'', 'class'=>'overrideNextDue overrideEom', 'checked'=>$override)); ?> <span style="font-size: 10px;">Override Next Due</span>
                                                                </div>

                                                                <div class="col-md-5 col-sm-5 col-xs-12">
                                                                    <?php echo $this->Form->checkbox('eom', array('label'=>'', 'class'=>'eomNextDue overrideEom', 'checked'=>$eom, 'readonly'=>'readonly')); ?> <span style="font-size: 10px;">EoM Adj</span>
                                                                </div>
                                                            </div>

                                                            <div class="form-group"> 
                                                                <label class="col-md-4 col-sm-4 col-xs-12" for="next_due_date">Date 
                                                                </label>
                                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                                    <?php echo $this->Form->control('next_due_date', array('class' => 'form-control col-md-7 col-xs-12 next-due-date '.$style, 'label'=>false, 'div'=>false, 'value'=>$mos, 'readonly'=>$readonly)); ?>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="col-md-4 col-sm-4 col-xs-12" for="next_due_hrs">Hours
                                                                </label>
                                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                                    <?php echo $this->Form->control('next_due_hrs', array('class'=>'form-control col-md-7 col-xs-12 next-due-hrs remUpdate '.$style, 'placeholder'=>'', 'label'=> false, 'value'=>$hrs, 'readonly' => $readonly)); ?>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="col-md-4 col-sm-4 col-xs-12" for="next_due_afl">Cycles
                                                                </label>
                                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                                    <?php echo $this->Form->control('next_due_afl', array('class'=>'form-control col-md-7 col-xs-12 next-due-afl remUpdate '.$style, 'placeholder'=>'', 'label'=> false, 'value'=>$afl, 'readonly' => $readonly)); ?>
                                                                </div>
                                                            </div>                            
                                                        </div>

                                                        <div class="itemCls">
                                                            <div class="form-group"> 
                                                                <label class="col-md-4 col-sm-4 col-xs-12">Months 
                                                                </label>
                                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                                    <span class="form-control remMos <?php echo $style; ?>">
                                                                        <?php echo $remMonths; ?>
                                                                    </span>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="col-md-4 col-sm-4 col-xs-12">Days
                                                                </label>
                                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                                    <span class="form-control remDays <?php echo $style; ?>">
                                                                        <?php echo $remDays; ?>
                                                                    </span>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="col-md-4 col-sm-4 col-xs-12">Hours
                                                                </label>
                                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                                    <span class="form-control remHrs <?php echo $style; ?>">
                                                                        <?php echo round($remHours,1); ?>
                                                                    </span>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="col-md-4 col-sm-4 col-xs-12">Cycles
                                                                </label>
                                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                                    <span class="form-control remAfl <?php echo $style; ?>">
                                                                        <?php echo $remCycles; ?>
                                                                    </span>
                                                                </div>
                                                            </div>                                
                                                        </div>

                                                        <div class="itemCls">
                                                            <div class="form-group">
                                                                <label class="col-md-4 col-sm-4 col-xs-12" for="adjustment_mos">Months
                                                                </label>
                                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                                    <?php echo $this->Form->control('adjustment_mos', array('class' => 'form-control col-md-7 col-xs-12 adjustment-mos keypress', 'label'=>false, 'value'=>!empty($airCompParts['airframe_component_last_cw'][0]['adjustment_mos'])?$airCompParts['airframe_component_last_cw'][0]['adjustment_mos']:'')); ?>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="col-md-4 col-sm-4 col-xs-12" for="adjustment_days">Days
                                                                </label>
                                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                                    <?php echo $this->Form->control('adjustment_days', array('class'=>'form-control col-md-7 col-xs-12 adjustment-days keypress', 'label'=> false, 'value'=>!empty($airCompParts['airframe_component_last_cw'][0]['adjustment_days'])?$airCompParts['airframe_component_last_cw'][0]['adjustment_days']:'')); ?>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="col-md-4 col-sm-4 col-xs-12" for="adjustment_hrs">Hours
                                                                </label>
                                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                                    <?php echo $this->Form->control('adjustment_hrs', array('class'=>'form-control col-md-7 col-xs-12 adjustment-hrs keypress', 'label'=> false, 'value'=>!empty($airCompParts['airframe_component_last_cw'][0]['adjustment_hrs'])?$airCompParts['airframe_component_last_cw'][0]['adjustment_hrs']:'')); ?>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="col-md-4 col-sm-4 col-xs-12" for="adjustment_afl">Cycles
                                                                </label>
                                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                                    <?php echo $this->Form->control('adjustment_afl', array('class'=>'form-control col-md-7 col-xs-12 adjustment-afl keypress', 'label'=> false, 'value'=>!empty($airCompParts['airframe_component_last_cw'][0]['adjustment_afl'])?$airCompParts['airframe_component_last_cw'][0]['adjustment_afl']:'')); ?>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="itemCls">
                                                            <div class="form-group">
                                                                <label class="col-md-4 col-sm-4 col-xs-12" for="required_frequency_mos">Months
                                                                </label>
                                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                                    <?php echo $this->Form->control('required_frequency_mos', array('class'=>'form-control col-md-7 col-xs-12 required-frequency-mos', 'label'=>false, 'value'=>!empty($airCompParts['airframe_component_last_cw'][0]['required_frequency_mos'])?$airCompParts['airframe_component_last_cw'][0]['required_frequency_mos']:'', 'readonly'=>'readonly')); ?>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="col-md-4 col-sm-4 col-xs-12" for="required_frequency_days">Days
                                                                </label>
                                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                                    <?php echo $this->Form->control('required_frequency_days', array('class'=> 'form-control col-md-7 col-xs-12 required-frequency-days', 'label'=>false, 'value'=>!empty($airCompParts['airframe_component_last_cw'][0]['required_frequency_days'])?$airCompParts['airframe_component_last_cw'][0]['required_frequency_days']:'', 'readonly'=>'readonly')); ?>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="col-md-4 col-sm-4 col-xs-12" for="required_frequency_hrs">Hours
                                                                </label>
                                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                                    <?php echo $this->Form->control('required_frequency_hrs', array('class'=>'form-control col-md-7 col-xs-12 required-frequency-hrs', 'label'=>false, 'value'=>!empty($airCompParts['airframe_component_last_cw'][0]['required_frequency_hrs'])?$airCompParts['airframe_component_last_cw'][0]['required_frequency_hrs']:'', 'readonly'=>'readonly')); ?>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="col-md-4 col-sm-4 col-xs-12" for="required_frequency_afl">Cycles
                                                                </label>
                                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                                    <?php echo $this->Form->control('required_frequency_afl', array('class'=>'form-control col-md-7 col-xs-12 required-frequency-afl', 'label'=>false, 'value'=>!empty($airCompParts['airframe_component_last_cw'][0]['required_frequency_afl'])?$airCompParts['airframe_component_last_cw'][0]['required_frequency_afl']:'', 'readonly'=>'readonly')); ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div style="clear: both;"></div>

                                                <div class="item-info">
                                                    <div class="addPageHeading">Work Description</div>

                                                    <div class="form-group">
                                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                                            <?php echo $this->Form->control('work_description', array('class'=>'form-control col-md-7 col-xs-12', 'label'=>false, 'rows'=>3, 'value'=>'')); ?>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col-md-1 col-sm-1 col-xs-12" for="avg_man_hrs">Man Hours
                                                        </label>
                                                        <div class="col-md-3 col-sm-3 col-xs-12">
                                                            <?php echo $this->Form->control('avg_man_hrs', array('class'=>'form-control col-md-7 col-xs-12', 'label'=>false, 'value'=>'')); ?>
                                                        </div>

                                                        <div class="col-md-3 col-sm-3 col-xs-12">
                                                            <?php echo $this->Form->checkbox('discrepancy_status', array('label'=>false)); ?> Discrepancy Found
                                                        </div>                            
                                                    </div>
                                                </div>

                                                <div class="item-info">
                                                    <div class="addPageHeading">Part Information</div>

                                                    <div class="partInfoCls">
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
                                                            New <input type="radio" class="newTypeId" name="part_type" value="new" <?php if(!empty($partType) && $partType == "new"){echo "checked";}?>>
                                                        </div>

                                                        <div class="itemCls">
                                                            Overhaul <input type="radio" class="overhaulTypeId" name="part_type" value="overhaul" <?php if(!empty($partType) && $partType == "overhaul"){echo "checked";}?>>
                                                        </div>

                                                        <div class="itemCls">
                                                            Repair <input type="radio" class="repairTypeId" name="part_type" value="repair" <?php if(!empty($partType) && $partType == "repair"){echo "checked";}?>>
                                                        </div>
                                                    </div>

                                                    <div class="partInfoCls">
                                                        <div class="itemCls">
                                                            Part Number
                                                        </div>

                                                        <div class="itemCls">
                                                            <?php echo $this->Form->control('removed_part_number', array('class' => 'form-control col-xs-12', 'label' => false, 'value'=>!empty($airCompParts['part_installed_times'][0]['removed_part_number']) ? $airCompParts['part_installed_times'][0]['removed_part_number'] : '')); ?>
                                                        </div>

                                                        <div class="itemCls">
                                                            <?php echo $this->Form->control('part_number', array('class' => 'form-control col-xs-12', 'label' => false)); ?>
                                                        </div>

                                                        <div class="itemCls">
                                                            Months
                                                        </div>

                                                        <div class="itemCls">
                                                            <?php echo $this->Form->control('new_months', array('class' => 'form-control col-md-7 col-xs-12 new-months ohkeypressDt norCalCls', 'placeholder' => 'mm/dd/yyyy or mm', 'label' => false, 'value'=>(!empty($airCompParts['part_installed_times'][0]['new_months']) || (isset($airCompParts['part_installed_times'][0]['new_months']) && $airCompParts['part_installed_times'][0]['new_months'] == 0)) ? $airCompParts['part_installed_times'][0]['new_months'] : '')); ?>
                                                        </div>

                                                        <div class="itemCls">
                                                           <?php echo $this->Form->control('overhaul_months', array('class' => 'form-control col-md-7 col-xs-12 overhaul-months ohkeypressDt norCalCls', 'placeholder' => 'mm/dd/yyyy or mm', 'label' => false, 'value'=>(!empty($airCompParts['part_installed_times'][0]['overhaul_months']) || (isset($airCompParts['part_installed_times'][0]['overhaul_months']) && $airCompParts['part_installed_times'][0]['overhaul_months'] == 0)) ? $airCompParts['part_installed_times'][0]['overhaul_months'] : '')); ?>
                                                        </div>

                                                        <div class="itemCls">
                                                            <?php echo $this->Form->control('repair_months', array('class' => 'form-control col-md-7 col-xs-12 repair-months ohkeypressDt norCalCls', 'placeholder' => 'mm/dd/yyyy or mm', 'label' => false, 'value'=>(!empty($airCompParts['part_installed_times'][0]['repair_months']) || (isset($airCompParts['part_installed_times'][0]['repair_months']) && $airCompParts['part_installed_times'][0]['repair_months'] == 0)) ? $airCompParts['part_installed_times'][0]['repair_months'] : '')); ?>
                                                        </div>
                                                    </div>

                                                    <div class="partInfoCls">
                                                        <div class="itemCls">
                                                            Serial Number
                                                        </div>

                                                        <div class="itemCls">
                                                            <?php echo $this->Form->control('removed_serial_number', array('class' => 'form-control col-xs-12', 'label' => false, 'value'=>!empty($airCompParts['part_installed_times'][0]['removed_serial_number']) ? $airCompParts['part_installed_times'][0]['removed_serial_number'] : '')); ?>
                                                        </div>

                                                        <div class="itemCls">
                                                            <?php echo $this->Form->control('serial_number', array('class' => 'form-control col-xs-12', 'label' => false)); ?>
                                                        </div>

                                                        <div class="itemCls">
                                                            Hours
                                                        </div>

                                                        <div class="itemCls">
                                                            <?php echo $this->Form->control('new_hours', array('class' => 'form-control col-md-7 col-xs-12 new-hours ohkeypress', 'placeholder' => '', 'label' => false, 'value'=>(!empty($airCompParts['part_installed_times'][0]['new_hours']) || (isset($airCompParts['part_installed_times'][0]['new_hours']) && $airCompParts['part_installed_times'][0]['new_hours'] == 0)) ? $airCompParts['part_installed_times'][0]['new_hours'] : '')); ?>
                                                        </div>

                                                        <div class="itemCls">
                                                           <?php echo $this->Form->control('overhaul_hours', array('class' => 'form-control col-md-7 col-xs-12 overhaul-hours ohkeypress', 'placeholder' => '', 'label' => false, 'value'=>(!empty($airCompParts['part_installed_times'][0]['overhaul_hours']) || (isset($airCompParts['part_installed_times'][0]['overhaul_hours']) && $airCompParts['part_installed_times'][0]['overhaul_hours'] == 0)) ? $airCompParts['part_installed_times'][0]['overhaul_hours'] : '')); ?>
                                                        </div>

                                                        <div class="itemCls">
                                                            <?php echo $this->Form->control('repair_hours', array('class' => 'form-control col-md-7 col-xs-12 repair-hours ohkeypress', 'placeholder' => '', 'label' => false, 'value'=>(!empty($airCompParts['part_installed_times'][0]['repair_hours']) || (isset($airCompParts['part_installed_times'][0]['repair_hours']) && $airCompParts['part_installed_times'][0]['repair_hours'] == 0)) ? $airCompParts['part_installed_times'][0]['repair_hours'] : '')); ?>
                                                        </div>
                                                    </div>

                                                    <div class="partInfoCls">
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
                                                            <?php echo $this->Form->control('new_landings', array('class' => 'form-control col-md-7 col-xs-12 new-landings ohkeypress', 'placeholder' => '', 'label' => false, 'value'=>(!empty($airCompParts['part_installed_times'][0]['new_landings']) || (isset($airCompParts['part_installed_times'][0]['new_landings']) && $airCompParts['part_installed_times'][0]['new_landings'] == 0)) ? $airCompParts['part_installed_times'][0]['new_landings'] : '')); ?>
                                                        </div>

                                                        <div class="itemCls">
                                                           <?php echo $this->Form->control('overhaul_landings', array('class' => 'form-control col-md-7 col-xs-12 overhaul-landings ohkeypress', 'placeholder' => '', 'label' => false, 'value'=>(!empty($airCompParts['part_installed_times'][0]['overhaul_landings']) || (isset($airCompParts['part_installed_times'][0]['overhaul_landings']) && $airCompParts['part_installed_times'][0]['overhaul_landings'] == 0)) ? $airCompParts['part_installed_times'][0]['overhaul_landings'] : '')); ?>
                                                        </div>

                                                        <div class="itemCls">
                                                            <?php echo $this->Form->control('repair_landings', array('class' => 'form-control col-md-7 col-xs-12 repair-landings ohkeypress', 'placeholder' => '', 'label' => false, 'value'=>(!empty($airCompParts['part_installed_times'][0]['repair_landings']) || (isset($airCompParts['part_installed_times'][0]['repair_landings']) && $airCompParts['part_installed_times'][0]['repair_landings'] == 0)) ? $airCompParts['part_installed_times'][0]['repair_landings'] : '')); ?>
                                                        </div>
                                                    </div>

                                                    <div class="partInfoCls">
                                                        <div class="itemCls">
                                                        </div>

                                                        <div class="itemCls">
                                                            <?php echo $this->Form->control('removal_reason', array('options' => $removalReason, 'empty' => 'Enter reason', 'class' => 'form-control col-md-7 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'value'=>!empty($airCompParts['part_installed_times'][0]['removal_reason']) ? $airCompParts['part_installed_times'][0]['removal_reason'] : '')); ?>
                                                        </div>

                                                        <div class="itemCls">
                                                        <?php
                                                            $installStatus = [
                                                                                'Altered'=>'[A] Altered',
                                                                                'Inspected'=>'[I] Inspected',
                                                                                'Modified'=>'[M] Modified',
                                                                                'New'=>'[N] New',
                                                                                'Not Specified'=>'[NS] Not Specified',
                                                                                'Overhauled'=>'[O] Overhauled',
                                                                                'Other'=>'[OT] Other (See Notes)',
                                                                                'Repaired'=>'[R] Repaired',
                                                                                'Rebuilt'=>'[RE] Rebuilt',
                                                                                'Serviceable'=>'[S] Serviceable'
                                                                            ];
                                                            echo $this->Form->control('installed_status', array('options' => $installStatus, 'empty' => 'Enter status', 'class' => 'form-control col-md-7 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'value'=>!empty($airCompParts['installed_status']) ? $airCompParts['installed_status'] : ''));
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

                                                <div class="item-info">
                                                    <div class="addPageHeading">Notes</div>

                                                    <div class="form-group">
                                                        <label class="col-md-2 col-sm-2 col-xs-12" for="notes">Regular Notes</label>
                                                        <div class="col-md-10 col-sm-10 col-xs-12">
                                                            <?php echo $this->Form->control('notes', array('class' => 'form-control col-md-7 col-xs-12', 'label' => false, 'rows'=>3, 'value'=>'')); ?>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col-md-2 col-sm-2 col-xs-12" for="admin_notes">Admin Notes</label>
                                                        <div class="col-md-10 col-sm-10 col-xs-12">
                                                            <?php echo $this->Form->control('admin_notes', array('class' => 'form-control col-md-7 col-xs-12', 'label' => false, 'rows'=>3, 'value'=>'')); ?>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="ln_solid"></div>
                                                <?php echo $this->Form->control('updated_by', array('type' => 'hidden', 'value' => $sessionArray['id'], 'label'=> false)); ?>

                                                <?php echo $this->Form->control('airframe_component_last_cw_id', array('type' => 'hidden', 'value'=>!empty($airCompParts['airframe_component_last_cw'][0]['id'])?$airCompParts['airframe_component_last_cw'][0]['id']:'', 'label'=> false)); ?>
                                            </div>

                                            <div id="subItems<?php echo $i; ?>" class="tab-pane fade">
                                                <div class="corrPageHeading">
                                                    <div class="corrPH">Tolerance</div>
                                                    <div class="corrPH">Recurring</div>
                                                    <div class="corrPH">Threshold</div>
                                                    <div class="corrPH">Interval</div>
                                                    <div class="corrPH">Additional Information</div>
                                                </div>
                                                <div class="itemInfoCls">
                                                    <div class="itemCls">
                                                        <div class="form-group">
                                                            <label class="col-md-4 col-sm-4 col-xs-12" for="tolerance_mos">Months
                                                            </label>
                                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                                <?php echo $this->Form->control('tolerance_mos', array('class'=>'form-control col-md-7 col-xs-12 tolerance-mos', 'label'=>false, 'value'=>!empty($airCompParts['airframe_component_last_cw'][0]['tolerance_mos'])?$airCompParts['airframe_component_last_cw'][0]['tolerance_mos']:'', 'readonly'=>'readonly')); ?>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="col-md-4 col-sm-4 col-xs-12" for="tolerance_days">Days
                                                            </label>
                                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                                <?php echo $this->Form->control('tolerance_days', array('class' => 'form-control col-md-7 col-xs-12 tolerance-days', 'label'=>false, 'value'=>!empty($airCompParts['airframe_component_last_cw'][0]['tolerance_days'])?$airCompParts['airframe_component_last_cw'][0]['tolerance_days']:'', 'readonly' => 'readonly')); ?>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="col-md-4 col-sm-4 col-xs-12" for="tolerance_hrs">Hours
                                                            </label>
                                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                                <?php echo $this->Form->control('tolerance_hrs', array('class'=>'form-control col-md-7 col-xs-12 tolerance-hrs', 'label'=>false, 'value'=>!empty($airCompParts['airframe_component_last_cw'][0]['tolerance_hrs'])?$airCompParts['airframe_component_last_cw'][0]['tolerance_hrs']:'', 'readonly' => 'readonly')); ?>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="col-md-4 col-sm-4 col-xs-12" for="tolerance_afl">Cycles
                                                            </label>
                                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                                <?php echo $this->Form->control('tolerance_afl', array('class'=>'form-control col-md-7 col-xs-12 tolerance-afl', 'label'=>false, 'value'=>!empty($airCompParts['airframe_component_last_cw'][0]['tolerance_afl'])?$airCompParts['airframe_component_last_cw'][0]['tolerance_afl']:'', 'readonly'=>'readonly')); ?>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="itemCls">
                                                        <div class="form-group">
                                                            <label class="col-md-4 col-sm-4 col-xs-12" for="recurring_mos">Months
                                                            </label>
                                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                                <?php echo $this->Form->control('recurring_mos', array('class'=>'form-control col-md-7 col-xs-12 recurring-mos', 'label'=>false, 'value'=>!empty($airCompParts['airframe_component_last_cw'][0]['recurring_mos'])?$airCompParts['airframe_component_last_cw'][0]['recurring_mos']:'', 'readonly' => 'readonly')); ?>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="col-md-4 col-sm-4 col-xs-12" for="recurring_days">Days
                                                            </label>
                                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                                <?php echo $this->Form->control('recurring_days', array('class'=>'form-control col-md-7 col-xs-12 recurring-days', 'label'=>false, 'value'=>!empty($airCompParts['airframe_component_last_cw'][0]['recurring_days'])?$airCompParts['airframe_component_last_cw'][0]['recurring_days']:'', 'readonly' => 'readonly')); ?>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="col-md-4 col-sm-4 col-xs-12" for="recurring_hrs">Hours
                                                            </label>
                                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                                <?php echo $this->Form->control('recurring_hrs', array('class'=>'form-control col-md-7 col-xs-12 recurring-hrs', 'label'=>false, 'value'=>!empty($airCompParts['airframe_component_last_cw'][0]['recurring_hrs'])?$airCompParts['airframe_component_last_cw'][0]['recurring_hrs']:'', 'readonly' => 'readonly')); ?>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="col-md-4 col-sm-4 col-xs-12" for="recurring_afl">Cycles
                                                            </label>
                                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                                <?php echo $this->Form->control('recurring_afl', array('class'=>'form-control col-md-7 col-xs-12 recurring-afl', 'label'=>false, 'value'=>!empty($airCompParts['airframe_component_last_cw'][0]['recurring_afl'])?$airCompParts['airframe_component_last_cw'][0]['recurring_afl']:'', 'readonly' => 'readonly')); ?>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="itemCls">
                                                        <div class="form-group">
                                                            <label class="col-md-4 col-sm-4 col-xs-12" for="threshold_mos">Months
                                                            </label>
                                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                                <?php echo $this->Form->control('threshold_mos', array('class'=>'form-control col-md-7 col-xs-12 threshold-mos', 'label'=>false, 'value'=>!empty($airCompParts['airframe_component_last_cw'][0]['threshold_mos'])?$airCompParts['airframe_component_last_cw'][0]['threshold_mos']:'', 'readonly' => 'readonly')); ?>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="col-md-4 col-sm-4 col-xs-12" for="threshold_days">Days
                                                            </label>
                                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                                <?php echo $this->Form->control('threshold_days', array('class' => 'form-control col-md-7 col-xs-12 threshold-days', 'label'=>false, 'value'=>!empty($airCompParts['airframe_component_last_cw'][0]['threshold_days'])?$airCompParts['airframe_component_last_cw'][0]['threshold_days']:'', 'readonly' => 'readonly')); ?>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="col-md-4 col-sm-4 col-xs-12" for="threshold_hrs">Hours
                                                            </label>
                                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                                <?php echo $this->Form->control('threshold_hrs', array('class'=>'form-control col-md-7 col-xs-12 threshold-hrs', 'label'=>false, 'value'=>!empty($airCompParts['airframe_component_last_cw'][0]['threshold_hrs'])?$airCompParts['airframe_component_last_cw'][0]['threshold_hrs']:'', 'readonly' => 'readonly')); ?>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="col-md-4 col-sm-4 col-xs-12" for="threshold_afl">Cycles
                                                            </label>
                                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                                <?php echo $this->Form->control('threshold_afl', array('class'=>'form-control col-md-7 col-xs-12 threshold-afl', 'label'=>false, 'value'=>!empty($airCompParts['airframe_component_last_cw'][0]['threshold_afl'])?$airCompParts['airframe_component_last_cw'][0]['threshold_afl']:'', 'readonly' => 'readonly')); ?>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="itemCls">
                                                        <div class="form-group">
                                                            <label class="col-md-4 col-sm-4 col-xs-12" for="required_frequency_mos">Months
                                                            </label>
                                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                                <?php echo $this->Form->control('required_frequency_mos', array('class'=>'form-control col-md-7 col-xs-12 required-frequency-mos', 'label'=>false, 'value'=>!empty($airCompParts['airframe_component_last_cw'][0]['required_frequency_mos'])?$airCompParts['airframe_component_last_cw'][0]['required_frequency_mos']:'', 'readonly'=>'readonly')); ?>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="col-md-4 col-sm-4 col-xs-12" for="required_frequency_days">Days
                                                            </label>
                                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                                <?php echo $this->Form->control('required_frequency_days', array('class'=> 'form-control col-md-7 col-xs-12 required-frequency-days', 'label'=>false, 'value'=>!empty($airCompParts['airframe_component_last_cw'][0]['required_frequency_days'])?$airCompParts['airframe_component_last_cw'][0]['required_frequency_days']:'', 'readonly'=>'readonly')); ?>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="col-md-4 col-sm-4 col-xs-12" for="required_frequency_hrs">Hours
                                                            </label>
                                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                                <?php echo $this->Form->control('required_frequency_hrs', array('class'=>'form-control col-md-7 col-xs-12 required-frequency-hrs', 'label'=>false, 'value'=>!empty($airCompParts['airframe_component_last_cw'][0]['required_frequency_hrs'])?$airCompParts['airframe_component_last_cw'][0]['required_frequency_hrs']:'', 'readonly'=>'readonly')); ?>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="col-md-4 col-sm-4 col-xs-12" for="required_frequency_afl">Cycles
                                                            </label>
                                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                                <?php echo $this->Form->control('required_frequency_afl', array('class'=>'form-control col-md-7 col-xs-12 required-frequency-afl', 'label'=>false, 'value'=>!empty($airCompParts['airframe_component_last_cw'][0]['required_frequency_afl'])?$airCompParts['airframe_component_last_cw'][0]['required_frequency_afl']:'', 'readonly'=>'readonly')); ?>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="itemCls">
                                                        <div class="form-group">
                                                            <label class="col-md-4 col-sm-4 col-xs-12">Work Card</label>
                                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                                <?php echo $this->Form->control('work_card', array('class' => 'form-control col-md-7 col-xs-12', 'label' => false, 'readonly' => 'readonly')); ?>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="col-md-4 col-sm-4 col-xs-12">Position</label>
                                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                                <?php echo $this->Form->control('position', array('class' => 'form-control col-md-7 col-xs-12', 'label' => false, 'readonly' => 'readonly')); ?>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="col-md-4 col-sm-4 col-xs-12">Revision</label>
                                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                                <?php echo $this->Form->control('revision', array('class' => 'form-control col-md-7 col-xs-12', 'label' => false, 'readonly' => 'readonly')); ?>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="col-md-4 col-sm-4 col-xs-12">Version</label>
                                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                                <?php echo $this->Form->control('version', array('class' => 'form-control col-md-7 col-xs-12', 'label' => false, 'readonly' => 'readonly')); ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Tabs end -->
                            </div>
                        <?php 
                        echo $this->Form->end();
                        ?>
                        </div>                
                        <?php
                        if($partCount > 1) {
                        ?>    
                        <a class="mvleft carousel-control" href="#myCarousel" data-slide="prev">
                            <span class="glyphicon glyphicon-chevron-left"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="mvright carousel-control" href="#myCarousel" data-slide="next">
                            <span class="glyphicon glyphicon-chevron-right"></span>
                            <span class="sr-only">Next</span>
                        </a>
                        <?php } ?>
                    </div>
                <?php
                    $i++;
                } 
                ?>
            </div>
        </div>
    </div>
</div>

<!-- Component Report Time popup -->
<div id="compReportTimeModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%;">
        <div class="modal-content">
            <form method="post" id="compReportTimeFrm" method="POST">
                <div class="modal-header" style="background-color: #e5e5e5;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Apply Times</h4>
                </div>
                <div class="modal-body" style="min-height: 330px; max-height: 500px; overflow-y: auto;">
                    <?php echo $tHtml; ?>
                </div>
                <div class="modal-footer">
                    <span class="errorMsg"></span>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <input type="button" value="Apply To Selected" class="btn btn-primary applyToSeltBtn">
                    <input type="button" value="Apply To All Items" data-partids="<?php echo $params['partids']; ?>" class="btn btn-primary applyToAllBtn">
                </div>
            </form>
        </div>
    </div>
</div>

<script>    
$(document).ready(function() {
    $('.errorCorrectForm select.selectpicker').on('change', function(e) {
        $('.errorCorrectForm').validate().element($(this));
    });

    $('.norCalCls').datetimepicker({
        format: 'MM-DD-YYYY',
        useCurrent: false
    });
    
    /*** If compliance date, hours and cycles are changed ***/
    //Change compliance date
    $(".cwDatepicker").datetimepicker({
        useCurrent: false,
        format: 'MM-DD-YYYY'
    }).on('dp.change', function(e) {
        var a = moment(e.date._d);
        var lastCwMos = a.format('M-D-Y');
        var diffVal = 'false';
        var thisVal = $(this).parents('.active');
        
        if ($('input.overrideNextDue').is(':checked')) {   
            updateNextRemDuesIfOverride(lastCwMos, diffVal, thisVal);
        } else {
            updateNextRemDues(lastCwMos, diffVal, thisVal, thisVal);
        }
    });
    /*** If compliance date, hours and cycles are changed ***/

    //Change next due and remaining values based on recurring, threshold or adjustment values change
    $(".keypress").keyup(function() {
        var lastCwMos = $(this).parents('.active').find('.cwDatepicker').val(); 
        var diffVal = 'false';
        var thisVal = $(this).parents('.active');

        if ($('input.overrideNextDue').is(':checked')) {   
            updateNextRemDuesIfOverride(lastCwMos, diffVal, thisVal);
        } else {
            updateNextRemDues(lastCwMos, diffVal, thisVal);
        }
    });
    
    //When add value in part (New/Overhaul/Repair)
    $(".ohkeypressDt").datetimepicker({
        useCurrent: false,
        format: 'MM-DD-YYYY'
    }).on('dp.change', function(e) {
        var lastCwMos = $(this).parents('.active').find('.cwDatepicker').val();
        var diffVal = 'true';
        var thisVal = $(this).parents('.active');
        
        if ($('input.overrideNextDue').is(':checked')) {    
            updateNextRemDuesIfOverride(lastCwMos, diffVal, thisVal);
        } else {
            updateNextRemDues(lastCwMos, diffVal, thisVal, thisVal);
        }
    });

    $(".ohkeypress").keyup(function() {
        var lastCwMos = $(this).parents('.active').find('.cwDatepicker').val();
        var diffVal = 'true';
        var thisVal = $(this).parents('.active');
        
        if ($('input.overrideNextDue').is(':checked')) {   
            updateNextRemDuesIfOverride(lastCwMos, diffVal, thisVal);
        } else {
            updateNextRemDues(lastCwMos, diffVal, thisVal);
        }
    });
    
    //When add value in part (New/Overhaul/Repair) and any option selected
    $('input[name="part_type"]').on('change', function() {
        var lastCwMos = $(this).parents('.active').find('.cwDatepicker').val();
        var diffVal = 'true';
        var thisVal = $(this).parents('.active');
        
        if ($('input.overrideNextDue').is(':checked')) {   
            updateNextRemDuesIfOverride(lastCwMos, diffVal, thisVal);
        } else {
            updateNextRemDues(lastCwMos, diffVal, thisVal);
        }
    });

    //Override next due
    $('.overrideEom').on('click', function() {
        var partId      = $(this).parents('.active').find('.part_id').val();
        var isOverride  = $(this).parents('.active').find('input.overrideNextDue').is(':checked');
        var isEom       = $(this).parents('.active').find('input.eomNextDue').is(':checked');
        var lastCwMos   = $(this).parents('.active').find('.cwDatepicker').val(); 
        var lastCwHrs   = $(this).parents('.active').find('.last-cw-hrs').val();
        var lastCwAfl   = $(this).parents('.active').find('.last-cw-afl').val();

        var nextDueDate = $(this).parents('.active').find('.next-due-date');
        var nextDueHrs  = $(this).parents('.active').find('.next-due-hrs');
        var nextDueAfl  = $(this).parents('.active').find('.next-due-afl');
        var remMos      = $(this).parents('.active').find('.remMos');
        var remDays     = $(this).parents('.active').find('.remDays');
        var remHrs      = $(this).parents('.active').find('.remHrs');
        var remAfl      = $(this).parents('.active').find('.remAfl');
        var diffVal     = 'false';
        var thisVal     = $(this).parents('.active');
        
        if ($('input.overrideNextDue').is(':checked')) {
            $.ajax({
                type: "POST",
                url: "<?php echo Router::url(['controller'=>'Compliance', 'action'=>'errorCNextdueRem']); ?>",
                data: {
                    partId: partId, 
                    isOverride: isOverride, 
                    isEom: isEom, 
                    lastCwMos: lastCwMos, 
                    lastCwHrs: lastCwHrs, 
                    lastCwAfl: lastCwAfl 
                },
                async: true,
                success: function(response) {
                    var obj = JSON.parse(response);
                    if(obj.status == 'success') {
                        if(obj.data.nextMos != '') {
                            nextDueDate.val(obj.data.nextMos).prop('readonly', false);
                        } else if(isEom) {
                            nextDueDate.prop('readonly', false);
                        } else { 
                            nextDueDate.val('').prop('readonly', false);
                        }

                        if(obj.data.nextMos != '') {
                            nextDueHrs.val(obj.data.nextHrs).prop('readonly', false);
                        } else if(isEom) {
                            nextDueHrs.prop('readonly', false);
                        } else {
                            nextDueHrs.val('').prop('readonly', false);
                        }

                        if(obj.data.nextMos != '') {
                            nextDueAfl.val(obj.data.nextAfl).prop('readonly', false);
                        } else if(isEom) {
                            nextDueAfl.prop('readonly', false);
                        } else {
                            nextDueAfl.val('').prop('readonly', false);
                        }
                        
                        if(obj.data.override == 1) {
                            remMos.html(obj.data.remMos);
                            remDays.html(obj.data.remDays);
                            remHrs.html(obj.data.remHrs);
                            remAfl.html(obj.data.remAfl);
                        }
                    }
                }                   
            });
        } else {
            //Update next due and remaining due
            updateNextRemDues(lastCwMos, diffVal, thisVal);
        }
    });

    //Update next due and remaining due if override
    function updateNextRemDuesIfOverride(lastCwMos, diffVal, thisVal) {
        var partId      = thisVal.find('.part_id').val();
        var mos         = thisVal.find('.next-due-date').val();
        var hrs         = thisVal.find('.next-due-hrs').val();
        var afl         = thisVal.find('.next-due-afl').val();
        var intvMos     = thisVal.find('.required-frequency-mos').val();
        var intvDay     = thisVal.find('.required-frequency-days').val();
        var intvHrs     = thisVal.find('.required-frequency-hrs').val();
        var intvAfl     = thisVal.find('.required-frequency-afl').val();
        var intvAdjMos  = thisVal.find('.adjustment-mos').val();
        var intvAdjDay  = thisVal.find('.adjustment-days').val();
        var intvAdjHrs  = thisVal.find('.adjustment-hrs').val();
        var intvAdjAfl  = thisVal.find('.adjustment-afl').val();
        var isOverride  = thisVal.find('input.overrideNextDue').is(':checked');
        var isEom       = thisVal.find('input.eomNextDue').is(':checked'); 
        var lastCwHrs   = thisVal.find('.last-cw-hrs').val();
        var lastCwAfl   = thisVal.find('.last-cw-afl').val();

        var nextDueDate = thisVal.find('.next-due-date');
        var nextDueHrs  = thisVal.find('.next-due-hrs');
        var nextDueAfl  = thisVal.find('.next-due-afl');
        var remMos      = thisVal.find('.remMos');
        var remDays     = thisVal.find('.remDays');
        var remHrs      = thisVal.find('.remHrs');
        var remAfl      = thisVal.find('.remAfl');

        var AdjMosSet   = thisVal.find('.adjustment-mos');
        var AdjDaySet   = thisVal.find('.adjustment-days');
        var AdjHrsSet   = thisVal.find('.adjustment-hrs');
        var AdjAflSet   = thisVal.find('.adjustment-afl');
        
        if(diffVal == 'true') {
            var overhaulType = 'yes';
            var partTypeVal  = thisVal.find('input[name="part_type"]:checked').val();
            var partMos;
            var partHrs;
            var partAfl;
            if(partTypeVal == 'new') {
                partMos = thisVal.find('.new-months').val();
                partHrs = thisVal.find('.new-hours').val();
                partAfl = thisVal.find('.new-landings').val();
            } else if(partTypeVal == 'overhaul') {
                partMos = thisVal.find('.overhaul-months').val();
                partHrs = thisVal.find('.overhaul-hours').val();
                partAfl = thisVal.find('.overhaul-landings').val();
            } else if(partTypeVal == 'repair') {
                partMos = thisVal.find('.repair-months').val();
                partHrs = thisVal.find('.repair-hours').val();
                partAfl = thisVal.find('.repair-landings').val();
            }

            var data = {
                    partId: partId, 
                    mos: mos, 
                    hrs: hrs, 
                    afl: afl, 
                    intvMos: intvMos, 
                    intvDay: intvDay, 
                    intvHrs: intvHrs, 
                    intvAfl: intvAfl, 
                    intvAdjMos: intvAdjMos, 
                    intvAdjDay: intvAdjDay, 
                    intvAdjHrs: intvAdjHrs, 
                    intvAdjAfl: intvAdjAfl, 
                    isOverride: isOverride, 
                    isEom: isEom, 
                    lastCwMos: lastCwMos, 
                    lastCwHrs: lastCwHrs, 
                    lastCwAfl: lastCwAfl,
                    overhaulType: overhaulType,
                    partTypeVal: partTypeVal,
                    partMos: partMos,
                    partHrs: partHrs,
                    partAfl: partAfl
                };
        } else {
            var data = {
                    partId: partId, 
                    mos: mos, 
                    hrs: hrs, 
                    afl: afl, 
                    intvMos: intvMos, 
                    intvDay: intvDay, 
                    intvHrs: intvHrs, 
                    intvAfl: intvAfl, 
                    intvAdjMos: intvAdjMos, 
                    intvAdjDay: intvAdjDay, 
                    intvAdjHrs: intvAdjHrs, 
                    intvAdjAfl: intvAdjAfl, 
                    isOverride: isOverride, 
                    isEom: isEom, 
                    lastCwMos: lastCwMos, 
                    lastCwHrs: lastCwHrs, 
                    lastCwAfl: lastCwAfl
                };
        }
        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(['controller'=>'Compliance', 'action'=>'errorCNextdueRem']); ?>",
            data: data,
            async: true,
            success: function(response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {

                    if(nextDueDate.val() == '' && nextDueHrs.val() == '' && nextDueAfl.val() == '') {
                        nextDueDate.prop('readonly', false);
                        nextDueHrs.prop('readonly', false);
                        nextDueAfl.prop('readonly', false);
                    } else {
                        nextDueDate.val(obj.data.nextMos).prop('readonly', false);
                        nextDueHrs.val(obj.data.nextHrs).prop('readonly', false);
                        nextDueAfl.val(obj.data.nextAfl).prop('readonly', false);
                    }
                    
                    remMos.html(obj.data.remMos);
                    remDays.html(obj.data.remDays);
                    remHrs.html(obj.data.remHrs);
                    remAfl.html(obj.data.remAfl);

                    if(data.overhaulType == 'yes') {
                        AdjMosSet.val(obj.data.intvAdjMos);
                        AdjDaySet.val(obj.data.intvAdjDay);
                        AdjHrsSet.val(obj.data.intvAdjHrs);
                        AdjAflSet.val(obj.data.intvAdjAfl);
                    }
                }
            }                   
        });
    }

    //Update next due and remaining due
    function updateNextRemDues(lastCwMos, diffVal, thisVal) {
        var partId      = thisVal.find('.part_id').val();
        var intvMos     = thisVal.find('.required-frequency-mos').val();
        var intvDay     = thisVal.find('.required-frequency-days').val();
        var intvHrs     = thisVal.find('.required-frequency-hrs').val();
        var intvAfl     = thisVal.find('.required-frequency-afl').val();
        var intvAdjMos  = thisVal.find('.adjustment-mos').val();
        var intvAdjDay  = thisVal.find('.adjustment-days').val();
        var intvAdjHrs  = thisVal.find('.adjustment-hrs').val();
        var intvAdjAfl  = thisVal.find('.adjustment-afl').val();
        var isOverride  = thisVal.find('input.overrideNextDue').is(':checked');
        var isEom       = thisVal.find('input.eomNextDue').is(':checked'); 
        var lastCwHrs   = thisVal.find('.last-cw-hrs').val();
        var lastCwAfl   = thisVal.find('.last-cw-afl').val();

        var nextDueDate = thisVal.find('.next-due-date');
        var nextDueHrs  = thisVal.find('.next-due-hrs');
        var nextDueAfl  = thisVal.find('.next-due-afl');
        var remMos      = thisVal.find('.remMos');
        var remDays     = thisVal.find('.remDays');
        var remHrs      = thisVal.find('.remHrs');
        var remAfl      = thisVal.find('.remAfl');

        var AdjMosSet   = thisVal.find('.adjustment-mos');
        var AdjDaySet   = thisVal.find('.adjustment-days');
        var AdjHrsSet   = thisVal.find('.adjustment-hrs');
        var AdjAflSet   = thisVal.find('.adjustment-afl');
        
        if(diffVal == 'true') {
            var overhaulType = 'yes';
            var partTypeVal  = thisVal.find('input[name="part_type"]:checked').val();
            var partMos;
            var partHrs;
            var partAfl;
            if(partTypeVal == 'new') {
                partMos = thisVal.find('.new-months').val();
                partHrs = thisVal.find('.new-hours').val();
                partAfl = thisVal.find('.new-landings').val();
            } else if(partTypeVal == 'overhaul') {
                partMos = thisVal.find('.overhaul-months').val();
                partHrs = thisVal.find('.overhaul-hours').val();
                partAfl = thisVal.find('.overhaul-landings').val();
            } else if(partTypeVal == 'repair') {
                partMos = thisVal.find('.repair-months').val();
                partHrs = thisVal.find('.repair-hours').val();
                partAfl = thisVal.find('.repair-landings').val();
            }

            var data = {
                    partId: partId, 
                    intvMos: intvMos, 
                    intvDay: intvDay, 
                    intvHrs: intvHrs, 
                    intvAfl: intvAfl, 
                    intvAdjMos: intvAdjMos, 
                    intvAdjDay: intvAdjDay, 
                    intvAdjHrs: intvAdjHrs, 
                    intvAdjAfl: intvAdjAfl, 
                    isOverride: isOverride, 
                    isEom: isEom, 
                    lastCwMos: lastCwMos, 
                    lastCwHrs: lastCwHrs, 
                    lastCwAfl: lastCwAfl,
                    overhaulType: overhaulType,
                    partTypeVal: partTypeVal,
                    partMos: partMos,
                    partHrs: partHrs,
                    partAfl: partAfl
                };
        } else {
            var data = {
                    partId: partId, 
                    intvMos: intvMos, 
                    intvDay: intvDay, 
                    intvHrs: intvHrs, 
                    intvAfl: intvAfl, 
                    intvAdjMos: intvAdjMos, 
                    intvAdjDay: intvAdjDay, 
                    intvAdjHrs: intvAdjHrs, 
                    intvAdjAfl: intvAdjAfl, 
                    isOverride: isOverride, 
                    isEom: isEom, 
                    lastCwMos: lastCwMos, 
                    lastCwHrs: lastCwHrs, 
                    lastCwAfl: lastCwAfl
                };
        }

        //Change next due & remaining
        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(['controller'=>'Compliance', 'action'=>'errorCNextdueRem']); ?>",
            data: data,
            async: true,
            success: function(response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    nextDueDate.val(obj.data.nextMos);
                    nextDueHrs.val(obj.data.nextHrs);
                    nextDueAfl.val(obj.data.nextAfl);
                    remMos.html(obj.data.remMos);
                    remDays.html(obj.data.remDays);
                    remHrs.html(obj.data.remHrs);
                    remAfl.html(obj.data.remAfl);

                    if(data.overhaulType == 'yes') {
                        AdjMosSet.val(obj.data.intvAdjMos);
                        AdjDaySet.val(obj.data.intvAdjDay);
                        AdjHrsSet.val(obj.data.intvAdjHrs);
                        AdjAflSet.val(obj.data.intvAdjAfl);
                    }
                }
            }                   
        });
    }

    //Next due input box value change
    $('.remUpdate').keyup(function() {
        var nextDueHrs = $(this).parents('.active').find('.next-due-hrs');
        var nextDueAfl = $(this).parents('.active').find('.next-due-afl');
        var remHrs     = $(this).parents('.active').find('.remHrs');
        var remAfl     = $(this).parents('.active').find('.remAfl');
        var compHours  = $(this).parents('.active').find('.compHours');
        var compCycles = $(this).parents('.active').find('.compCycles');

        if(nextDueHrs.val() != '') {
            var hrs = nextDueHrs.val() - compHours.val();
            remHrs.html(hrs.toFixed(2));
        } else {
            remHrs.html('');
        }

        if(nextDueAfl.val() != '') {
            var afl = nextDueAfl.val() - compCycles.val();
            remAfl.html(afl.toFixed(2));
        } else {
            remAfl.html('');
        }
    });

    //Change next due date
    $(".next-due-date").datetimepicker({
        useCurrent: false,
        format: 'MM-DD-YYYY'
    }).on('dp.change', function(e) {
        var a = moment(e.date._d);
        var date    = a.format('m-d-Y');
        var remMos  = $(this).parents('.active').find('.remMos');
        var remDays = $(this).parents('.active').find('.remDays');

        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(['controller'=>'AirframeComponentParts', 'action'=>'updateRemaining']); ?>",
            data: {nextMos: date},
            async: true,
            success: function(response) {
                console.log(response);
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    remMos.html(obj.data.remMos);
                    remDays.html(obj.data.remDays);
                }
            }                   
        });
    });

    //Get all aircraft details
    $('.partSelectorBtn').on('click', function(e) {
        $('.partDetailsPopup').toggle();
    });

    //Close div
    $('.closePartInfo').on('click', function() {
        $('.partDetailsPopup').hide();
    });

    //Checkbox script to count checkbox for remove paent/child relation
    $(".partCheckAll").click(function () {
        var partChkBoxCls  = $(this).parents('.active').find('.partChkBoxCls');
        var partChkBox_ckd = $(this).parents('.active').find("input.partChkBoxCls:checked");
        var removePartParent = $(this).parents('.active').find('.removePartParent');
        
        partChkBoxCls.prop('checked', $(this).prop('checked'));

        var vals = [];
        partChkBox_ckd.each(function() {
            vals.push($(this).val());
        });
        vals = $.unique(vals.sort());
        var leavepid = $(this).data('leavepid');
        vals = jQuery.grep(vals, function(value) {
            return value != leavepid;
        });
        removePartParent.data('partids',vals);
    });

    $(".partChkBoxCls").click(function () {
        var partChkBox_ckd = $(this).parents('.active').find("input.partChkBoxCls:checked");
        var removePartParent = $(this).parents('.active').find('.removePartParent');
        var partCheckAll = $(this).parents('.active').find('.partCheckAll');

        var totalCheckboxes = '<?php echo $partCount - 1; ?>';
        var checkedcount = partChkBox_ckd.length;
        
        if(totalCheckboxes == checkedcount) {
            partCheckAll.prop('checked', true);
        } else {
            partCheckAll.prop('checked', false);
        }
    
        var vals = [];
        partChkBox_ckd.each(function() {
            vals.push($(this).val());
        });
        vals = $.unique(vals.sort());
        removePartParent.data('partids',vals);
    });
    //Checkbox script to count checkbox for remove paent/child relation

    //Report time model on click compliance time link
    $(document).on("click", ".complienceTimeCls", function() {
        var partId = $(this).parents('.active').find('.part_id').val();
        var its_obj = $(this);
        $('.getselectedobj').data('selectedobj',its_obj);
        $('#compReportTimeModel').modal('show');
        $('#compReportTimeModel .partIdCls').val(partId);
        $('#compReportTimeModel .errorMsg').html('');
    });

    //Save item after change
    $(document).on("click", ".saveCompReport", function() {
        var d = new Date();
        var month = d.getMonth()+1;
        var day = d.getDate();
        var currentDate = d.getFullYear() + '-' + (month<10 ? '0' : '') + month + '-' + (day<10 ? '0' : '') + day;
        
        var appliedD1 = $('.cwDatepicker').val();
        var strArray = appliedD1.split("-");
        var appliedD = strArray[2] + '-' + strArray[0] + '-' + strArray[1];
        
        if(new Date(appliedD) <= new Date(currentDate)) {
            var type = $(this).data('type');
            
            var data = $('#compReportTimeFrm').serialize();

            if(type == 'all') {
                    $.ajax({
                        url : "<?php echo $this->Url->build(['controller'=>'Compliance', 'action'=>'updateCustomItems']); ?>",
                        type : 'post',
                        data : data,
                        beforeSend:function(){
                            $('.loader').show();
                        },
                        success:function(response) {
                            var obj = JSON.parse(response);
                            if(obj.status == 'success') {
                                setTimeout(function() {
                                    $('.loader').hide();
                                    $('.content').before('<div class="alert alert-success content sliding">'+obj.message+'<a href="#" class="close" onclick="$(this).parent().fadeOut();return false;">×</a></div>');
                                }, 500);
                            } else {
                                setTimeout(function() {
                                    $('.loader').hide();
                                }, 500);
                            }                   
                        }
                    });
            } else {
                $('.loader').show();
                setTimeout(function() {
                    $( ".active .errorCorrectForm" ).submit();
                    $('.loader').hide();
                }, 500);
            }
        } else {
            $(".myAlert-top").show();
            setTimeout(function(){
                $(".myAlert-top").hide(); 
            }, 2000);
        }
    });

    //Initiate after html load
    $(document).on('show.bs.modal','.modal', function () {
        //Display date and other selected values in all input fields
        $("#compReportTimeModel .logDatepicker").datetimepicker({
            useCurrent: true,
            format: 'MM-DD-YYYY'
        }).on('dp.change', function(e) {
            var a = moment(e.date._d);
            var date = a.format('M-D-Y');
            $(".logDatepicker").val(date);
        });

        //Apply to selected
        $('.applyToSeltBtn').on('click', function(e) {
            e.stopPropagation();
            e.preventDefault();
            var selectedobj = $('.getselectedobj').data('selectedobj');                        
            var data = $('#compReportTimeFrm').serialize();
            $.ajax({
                url : "<?php echo $this->Url->build(['controller'=>'Compliance', 'action'=>'applyToSelectCustom']); ?>",
                type : 'post',
                data : data,
                success:function(response) {
                    var obj = JSON.parse(response);
                    if(obj.status == 'success') { 
                        selectedobj.parents('.active').find('.cwDatepicker').val(obj.data.lastCwMos);
                        selectedobj.parents('.active').find('.last-cw-hrs').val(obj.data.lastCwHrs);
                        selectedobj.parents('.active').find('.last-cw-afl').val(obj.data.lastCwAfl);
                        selectedobj.parents('.active').find('.next-due-date').val(obj.data.nextMos);
                        selectedobj.parents('.active').find('.next-due-hrs').val(obj.data.nextHrs);
                        selectedobj.parents('.active').find('.next-due-afl').val(obj.data.nextAfl);
                        selectedobj.parents('.active').find('.remMos').html(obj.data.remMos);
                        selectedobj.parents('.active').find('.remDays').html(obj.data.remDays);
                        selectedobj.parents('.active').find('.remHrs').html(obj.data.remHrs);
                        selectedobj.parents('.active').find('.remAfl').html(obj.data.remAfl);
                        //selectedobj.parents('.active').find('.saveCompReport').data('selType', 'single');
                        
                        $('#compReportTimeModel').modal('hide');
                    }                   
                },
                error : function() {
                    //alert('Some error occured. Please try again!');
                    $('#compReportTimeModel').modal('hide');
                },
                complete: function () {
                    $('#compReportTimeModel').modal('hide');
                }
            });
        });

        //Apply to selected
        $('.applyToAllBtn').on('click', function(e) {
            e.stopPropagation();
            e.preventDefault();
            var selectedobj = $('.getselectedobj').data('selectedobj');

            var partids = $(this).data('partids');

            var data = $('#compReportTimeFrm').serialize();
            $.ajax({
                url : "<?php echo $this->Url->build(['controller'=>'Compliance', 'action'=>'applyToAllCustom']); ?>",
                type : 'post',
                data : data,
                success:function(response) {
                    var obj = JSON.parse(response);
                    if(obj.status == 'success') {                        
                        
                        var form = $('<form method="GET" action="<?php echo $this->Url->build(['controller'=>'Compliance', 'action'=>'addCustomCompliance']);?>">');
                        form.append('<input type="hidden" name="aircraftId" value="'+obj.data.planeId+'">');
                        form.append('<input type="hidden" name="partids" value="'+partids+'">');
                        form.append('<input type="hidden" name="log_date" value="'+obj.data.lastCwMos+'">');
                        form.append('<input type="hidden" name="hours" value="'+obj.data.lastCwHrs+'">');
                        form.append('<input type="hidden" name="cycles" value="'+obj.data.lastCwAfl+'">');
                        //form.append('<input type="hidden" name="addHrVal" value="'+obj.data.addHrVal+'">');
                        //form.append('<input type="hidden" name="addCyVal" value="'+obj.data.addCyVal+'">');
                        form.append('<input type="hidden" name="type" value="all">');
                        $('body').append(form);
                        form.submit();
                                    
                        $('#compReportTimeModel').modal('hide');
                    }                   
                },
                error : function() {
                    //alert('Some error occured. Please try again!');
                    $('#compReportTimeModel').modal('hide');
                },
                complete: function () {
                    $('#compReportTimeModel').modal('hide');
                }
            });
        });
    });
    
});    
</script>