<?php
$months = $pilotComp->getMonthsList();
?>
<div class="panel-heading">
<?php    
if($type == 'edit') {
    echo '<div class="panel-title">Training</div>';
} else {
    echo '<div class="panel-title">'.$pilotTraining.' Training</div>';
}
?>
</div>
<div class="panel-body">
    <div class="table-responsive">
        <table class="table table-borderless table-condensed table-hover">
            <tr>
                <th width="5%" <?php if($type == 'crew'){echo 'style="display:none"';} ?>></th>       
                <th <?php if($type == 'crew'){echo 'width="40%"';}else{echo 'width="35%"';} ?>>Aircraft Flight Training</th>
                <th width="20%">Base Month</th>
                <th width="15%">Frequency</th>
                <th width="15%">Last Completed</th>
                <th width="10%">Next Due</th>
            </tr>
            <tbody>
                <?php
                if(in_array(@$pilot['pilot_trainings'][0]['AFT_check'], $chk)) {
                ?>
                    <tr>
                        <td <?php if($type == 'crew'){echo "style='display:none'";} ?>>
                            <?php echo $this->Form->checkbox('AFT_check', array('checked'=>@$pilot['pilot_trainings'][0]['AFT_check'], 'label'=>'')); ?>
                        </td>
                        <td>P180</td>
                        <td>
                            <?php 
                            echo $this->Form->control('AFT_month', array('options' => $months, 'empty' => 'Select', 'class' => 'form-control col-md-7 col-xs-12 baseM', 'label' => false, 'value'=>@$pilot['pilot_trainings'][0]['AFT_month'])); 
                            ?>
                        </td>
                        <td>
                            <?php 
                            echo $this->Form->control('AFT_frequency', array('class' => 'form-control frequencyM', 'label'=>false, 'value'=>@$pilot['pilot_trainings'][0]['AFT_frequency'])); 
                            ?> 
                        </td>
                        <td>
                            <div class="input-group date datePicker">
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                            <?php 
                            echo $this->Form->Text('AFT_last_completed', array('class' => 'form-control', 'label' => false, 'value'=>!empty(@$pilot['pilot_trainings'][0]['AFT_last_completed']) ? date('m/d/Y', strtotime(@$pilot['pilot_trainings'][0]['AFT_last_completed'])) : '')); 
                            ?>
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                            <?php
                            //$aftNDue = $pilotComp->nextdueProcess(@$pilot['pilot_trainings'][0], 'AFT');
                            echo $this->Form->Text('AFT_nextdue', array('class'=>'form-control dateNextDue '.$aftNDue['colorcls'], 'label'=>false, 'value'=>$aftNDue['nextdue'], $aftNDue['disabled'])); 
                            echo $aftNDue['tooltip'];
                            ?>
                            <input type="hidden" name="AFT_nextdue_status" class="ndStatus" value="<?php echo @$pilot['pilot_trainings'][0]['AFT_nextdue_status']; ?>">
                            </div>
                        </td>
                    </tr>
                <?php
                }

                if(in_array(@$pilot['pilot_trainings'][0]['EDT_check'], $chk)) {
                ?>
                    <tr>
                        <td colspan="5"><b>Emergency Drill Training</b></td>
                    </tr>

                    <tr>
                        <td <?php if($type == 'crew'){echo "style='display:none'";} ?>>
                            <?php echo $this->Form->checkbox('EDT_check', array('checked'=>@$pilot['pilot_trainings'][0]['EDT_check'], 'label'=>'')); ?>
                        </td>
                        <td>P180</td>
                        <td>
                            <?php 
                            echo $this->Form->control('EDT_month', array('options' => $months, 'empty' => 'Select', 'class' => 'form-control col-md-7 col-xs-12 baseM', 'label' => false, 'value'=>@$pilot['pilot_trainings'][0]['EDT_month'])); 
                            ?>
                        </td>
                        <td>
                            <?php 
                            echo $this->Form->control('EDT_frequency', array('class' => 'form-control frequencyM', 'label'=>false, 'value'=>@$pilot['pilot_trainings'][0]['EDT_frequency'])); 
                            ?>
                        </td>
                        <td>
                            <div class="input-group date datePicker">
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                            <?php 
                            echo $this->Form->Text('EDT_last_completed', array('class' => 'form-control', 'label' => false, 'value'=>!empty(@$pilot['pilot_trainings'][0]['EDT_last_completed']) ? date('m/d/Y', strtotime(@$pilot['pilot_trainings'][0]['EDT_last_completed'])) : '')); 
                            ?>
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                            <?php
                            //$edtNDue = $pilotComp->nextdueProcess(@$pilot['pilot_trainings'][0], 'EDT');
                            echo $this->Form->Text('EDT_nextdue', array('class'=>'form-control dateNextDue '.$edtNDue['colorcls'], 'label'=>false, 'value'=>$edtNDue['nextdue'], $edtNDue['disabled'])); 
                            echo $edtNDue['tooltip'];
                            ?>
                            <input type="hidden" name="EDT_nextdue_status" class="ndStatus" value="<?php echo @$pilot['pilot_trainings'][0]['EDT_nextdue_status']; ?>">
                            </div>
                        </td>
                    </tr>
                <?php
                }
                ?>

                <?php
                if(in_array(@$pilot['pilot_trainings'][0]['CWOT_check'], $chk) || in_array(@$pilot['pilot_trainings'][0]['CRM_check'], $chk) || in_array(@$pilot['pilot_trainings'][0]['EFB_check'], $chk) || in_array(@$pilot['pilot_trainings'][0]['EGT_check'], $chk) || in_array(@$pilot['pilot_trainings'][0]['GIT_check'], $chk) || in_array(@$pilot['pilot_trainings'][0]['Hz_check'], $chk) || in_array(@$pilot['pilot_trainings'][0]['IR_check'], $chk) || in_array(@$pilot['pilot_trainings'][0]['IRG_check'], $chk) || in_array(@$pilot['pilot_trainings'][0]['ICAT_check'], $chk)|| in_array(@$pilot['pilot_trainings'][0]['LBFT_check'], $chk) || in_array(@$pilot['pilot_trainings'][0]['RVSM_check'], $chk) || in_array(@$pilot['pilot_trainings'][0]['ST_check'], $chk)) {
                ?>
                    <tr>
                        <td colspan="5"><b>Ground Training</b></td>
                    </tr>

                    <?php
                    if(in_array(@$pilot['pilot_trainings'][0]['CWOT_check'], $chk)) {
                    ?>
                    <tr>
                        <td <?php if($type == 'crew'){echo "style='display:none'";} ?>>
                            <?php echo $this->Form->checkbox('CWOT_check', array('checked'=>@$pilot['pilot_trainings'][0]['CWOT_check'], 'label'=>'')); ?>
                        </td>
                        <td>Cold Weather Operations Training</td>
                        <td>
                            <?php 
                            echo $this->Form->control('CWOT_month', array('options' => $months, 'empty' => 'Select', 'class' => 'form-control col-md-7 col-xs-12 baseM', 'label' => false, 'value'=>@$pilot['pilot_trainings'][0]['CWOT_month'])); 
                            ?>
                        </td>
                        <td>
                            <?php 
                            echo $this->Form->control('CWOT_frequency', array('class' => 'form-control frequencyM', 'label'=>false, 'value'=>@$pilot['pilot_trainings'][0]['CWOT_frequency'])); 
                            ?>
                        </td>
                        <td>
                            <div class="input-group date datePicker">
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                            <?php 
                            echo $this->Form->Text('CWOT_last_completed', array('class' => 'form-control', 'label' => false, 'value'=>!empty(@$pilot['pilot_trainings'][0]['CWOT_last_completed']) ? date('m/d/Y', strtotime(@$pilot['pilot_trainings'][0]['CWOT_last_completed'])) : '')); 
                            ?>
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                            <?php
                            //$cwotNDue = $pilotComp->nextdueProcess(@$pilot['pilot_trainings'][0], 'CWOT');
                            echo $this->Form->Text('CWOT_nextdue', array('class'=>'form-control dateNextDue '.$cwotNDue['colorcls'], 'label'=>false, 'value'=>$cwotNDue['nextdue'], $cwotNDue['disabled']));
                            echo $cwotNDue['tooltip'];
                            ?>
                            <input type="hidden" name="CWOT_nextdue_status" class="ndStatus" value="<?php echo @$pilot['pilot_trainings'][0]['CWOT_nextdue_status']; ?>">
                            </div>
                        </td>
                    </tr>
                    <?php
                    }

                    if(in_array(@$pilot['pilot_trainings'][0]['CRM_check'], $chk)) {
                    ?>
                    <tr>
                        <td <?php if($type == 'crew'){echo "style='display:none'";} ?>>
                            <?php echo $this->Form->checkbox('CRM_check', array('checked'=>@$pilot['pilot_trainings'][0]['CRM_check'], 'label'=>'')); ?>
                        </td>
                        <td>Crew Resource Management (CRM):</td>
                        <td>
                            <?php 
                            echo $this->Form->control('CRM_month', array('options' => $months, 'empty' => 'Select', 'class' => 'form-control col-md-7 col-xs-12 baseM', 'label' => false, 'value'=>@$pilot['pilot_trainings'][0]['CRM_month'])); 
                            ?>
                        </td>
                        <td>
                            <?php 
                            echo $this->Form->control('CRM_frequency', array('class' => 'form-control frequencyM', 'label'=>false, 'value'=>@$pilot['pilot_trainings'][0]['CRM_frequency'])); 
                            ?>
                        </td>
                        <td>
                            <div class="input-group date datePicker">
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                            <?php 
                            echo $this->Form->Text('CRM_last_completed', array('class' => 'form-control', 'label' => false, 'value'=>!empty(@$pilot['pilot_trainings'][0]['CRM_last_completed']) ? date('m/d/Y', strtotime(@$pilot['pilot_trainings'][0]['CRM_last_completed'])) : '')); 
                            ?>
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                            <?php
                            //$crmNDue = $pilotComp->nextdueProcess(@$pilot['pilot_trainings'][0], 'CRM');
                            echo $this->Form->Text('CRM_nextdue', array('class'=>'form-control dateNextDue '.$crmNDue['colorcls'], 'label'=>false, 'value'=>$crmNDue['nextdue'], $crmNDue['disabled']));
                            echo $crmNDue['tooltip'] ;
                            ?>
                            <input type="hidden" name="CRM_nextdue_status" class="ndStatus" value="<?php echo @$pilot['pilot_trainings'][0]['CRM_nextdue_status']; ?>">
                            </div>
                        </td>
                    </tr>
                    <?php
                    }

                    if(in_array(@$pilot['pilot_trainings'][0]['EFB_check'], $chk)) {
                    ?>
                    <tr>
                        <td <?php if($type == 'crew'){echo "style='display:none'";} ?>>
                            <?php echo $this->Form->checkbox('EFB_check', array('checked'=>@$pilot['pilot_trainings'][0]['EFB_check'], 'label'=>'')); ?>
                        </td>
                        <td>Electronic Flight Bag (EFB):</td>
                        <td>
                            <?php 
                            echo $this->Form->control('EFB_month', array('options' => $months, 'empty' => 'Select', 'class' => 'form-control col-md-7 col-xs-12 baseM', 'label' => false, 'value'=>@$pilot['pilot_trainings'][0]['EFB_month'])); 
                            ?>
                        </td>
                        <td>
                            <?php 
                            echo $this->Form->control('EFB_frequency', array('class' => 'form-control frequencyM', 'label'=>false, 'value'=>@$pilot['pilot_trainings'][0]['EFB_frequency'])); 
                            ?>
                        </td>
                        <td>
                            <div class="input-group date datePicker">
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                            <?php 
                            echo $this->Form->Text('EFB_last_completed', array('class' => 'form-control', 'label' => false, 'value'=>!empty(@$pilot['pilot_trainings'][0]['EFB_last_completed']) ? date('m/d/Y', strtotime(@$pilot['pilot_trainings'][0]['EFB_last_completed'])) : '')); 
                            ?>
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                            <?php
                            //$efbNDue = $pilotComp->nextdueProcess(@$pilot['pilot_trainings'][0], 'EFB');
                            echo $this->Form->Text('EFB_nextdue', array('class'=>'form-control dateNextDue '.$efbNDue['colorcls'], 'label'=>false, 'value'=>$efbNDue['nextdue'], $efbNDue['disabled'])); 
                            echo $efbNDue['tooltip'];
                            ?>
                            <input type="hidden" name="EFB_nextdue_status" class="ndStatus" value="<?php echo @$pilot['pilot_trainings'][0]['EFB_nextdue_status']; ?>">
                            </div>
                        </td>
                    </tr>
                    <?php
                    }

                    if(in_array(@$pilot['pilot_trainings'][0]['EGT_check'], $chk)) {
                    ?>
                    <tr>
                        <td <?php if($type == 'crew'){echo "style='display:none'";} ?>>
                            <?php echo $this->Form->checkbox('EGT_check', array('checked'=>@$pilot['pilot_trainings'][0]['EGT_check'], 'label'=>'')); ?>
                        </td>
                        <td>Emergency Ground Training:</td>
                        <td>
                            <?php 
                            echo $this->Form->control('EGT_month', array('options' => $months, 'empty' => 'Select', 'class' => 'form-control col-md-7 col-xs-12 baseM', 'label' => false, 'value'=>@$pilot['pilot_trainings'][0]['EGT_month'])); 
                            ?>
                        </td>
                        <td>
                            <?php 
                            echo $this->Form->control('EGT_frequency', array('class' => 'form-control frequencyM', 'label'=>false, 'value'=>@$pilot['pilot_trainings'][0]['EGT_frequency'])); 
                            ?>
                        </td>
                        <td>
                            <div class="input-group date datePicker">
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                            <?php 
                            echo $this->Form->Text('EGT_last_completed', array('class' => 'form-control', 'label' => false, 'value'=>!empty(@$pilot['pilot_trainings'][0]['EGT_last_completed']) ? date('m/d/Y', strtotime(@$pilot['pilot_trainings'][0]['EGT_last_completed'])) : '')); 
                            ?>
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                            <?php
                            //$egtNDue = $pilotComp->nextdueProcess(@$pilot['pilot_trainings'][0], 'EGT');
                            echo $this->Form->Text('EGT_nextdue', array('class'=>'form-control dateNextDue '.$egtNDue['colorcls'], 'label'=>false, 'value'=>$egtNDue['nextdue'], $egtNDue['disabled']));
                            echo $egtNDue['tooltip'] ;
                            ?>
                            <input type="hidden" name="EGT_nextdue_status" class="ndStatus" value="<?php echo @$pilot['pilot_trainings'][0]['EGT_nextdue_status']; ?>">
                            </div>
                        </td>
                    </tr>
                    <?php
                    }

                    if(in_array(@$pilot['pilot_trainings'][0]['GIT_check'], $chk)) {
                    ?>
                    <tr>
                        <td <?php if($type == 'crew'){echo "style='display:none'";} ?>>
                            <?php echo $this->Form->checkbox('GIT_check', array('checked'=>@$pilot['pilot_trainings'][0]['GIT_check'], 'label'=>'')); ?>
                        </td>
                        <td>Global/International Training:</td>
                        <td>
                            <?php 
                            echo $this->Form->control('GIT_month', array('options' => $months, 'empty' => 'Select', 'class' => 'form-control col-md-7 col-xs-12 baseM', 'label' => false, 'value'=>@$pilot['pilot_trainings'][0]['GIT_month'])); 
                            ?>
                        </td>
                        <td>
                            <?php 
                            echo $this->Form->control('GIT_frequency', array('class' => 'form-control frequencyM', 'label'=>false, 'value'=>@$pilot['pilot_trainings'][0]['GIT_frequency'])); 
                            ?>
                        </td>
                        <td>
                            <div class="input-group date datePicker">
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                            <?php 
                            echo $this->Form->Text('GIT_last_completed', array('class' => 'form-control', 'label' => false, 'value'=>!empty(@$pilot['pilot_trainings'][0]['GIT_last_completed']) ? date('m/d/Y', strtotime(@$pilot['pilot_trainings'][0]['GIT_last_completed'])) : '')); 
                            ?>
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                            <?php
                            //$gitNDue = $pilotComp->nextdueProcess(@$pilot['pilot_trainings'][0], 'GIT');
                            echo $this->Form->Text('GIT_nextdue', array('class'=>'form-control dateNextDue '.$gitNDue['colorcls'], 'label'=>false, 'value'=>$gitNDue['nextdue'], $gitNDue['disabled'])); 
                            echo $gitNDue['tooltip'];
                            ?>
                            <input type="hidden" name="GIT_nextdue_status" class="ndStatus" value="<?php echo @$pilot['pilot_trainings'][0]['GIT_nextdue_status']; ?>">
                            </div>
                        </td>
                    </tr>
                    <?php
                    }

                    if(in_array(@$pilot['pilot_trainings'][0]['Hz_check'], $chk)) {
                    ?>
                    <tr>
                        <td <?php if($type == 'crew'){echo "style='display:none'";} ?>>
                            <?php echo $this->Form->checkbox('Hz_check', array('checked'=>@$pilot['pilot_trainings'][0]['Hz_check'], 'label'=>'')); ?>
                        </td>
                        <td>Hazmat (Will Not Carry):</td>
                        <td>
                            <?php 
                            echo $this->Form->control('Hz_month', array('options' => $months, 'empty' => 'Select', 'class' => 'form-control col-md-7 col-xs-12 baseM', 'label' => false, 'value'=>@$pilot['pilot_trainings'][0]['Hz_month'])); 
                            ?>
                        </td>
                        <td>
                            <?php 
                            echo $this->Form->control('Hz_frequency', array('class' => 'form-control frequencyM', 'label'=>false, 'value'=>@$pilot['pilot_trainings'][0]['Hz_frequency'])); 
                            ?>
                        </td>
                        <td>
                            <div class="input-group date datePicker">
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                            <?php 
                            echo $this->Form->Text('Hz_last_completed', array('class' => 'form-control', 'label' => false, 'value'=>!empty(@$pilot['pilot_trainings'][0]['Hz_last_completed']) ? date('m/d/Y', strtotime(@$pilot['pilot_trainings'][0]['Hz_last_completed'])) : '')); 
                            ?>
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                            <?php
                            //$hzNDue = $pilotComp->nextdueProcess(@$pilot['pilot_trainings'][0], 'Hz');
                            echo $this->Form->Text('Hz_nextdue', array('class'=>'form-control dateNextDue '.$hzNDue['colorcls'], 'label'=>false, 'value'=>$hzNDue['nextdue'], $hzNDue['disabled']));
                            echo $hzNDue['tooltip'];
                            ?>
                            <input type="hidden" name="Hz_nextdue_status" class="ndStatus" value="<?php echo @$pilot['pilot_trainings'][0]['Hz_nextdue_status']; ?>">
                            </div>
                        </td>
                    </tr>
                    <?php
                    }

                    if(in_array(@$pilot['pilot_trainings'][0]['IR_check'], $chk)) {
                    ?>
                    <tr>
                        <td <?php if($type == 'crew'){echo "style='display:none'";} ?>>
                            <?php echo $this->Form->checkbox('IR_check', array('checked'=>@$pilot['pilot_trainings'][0]['IR_check'], 'label'=>'')); ?>
                        </td>
                        <td>Indoctrination/Recurrent 135:</td>
                        <td>
                            <?php 
                            echo $this->Form->control('IR_month', array('options' => $months, 'empty' => 'Select', 'class' => 'form-control col-md-7 col-xs-12 baseM', 'label' => false, 'value'=>@$pilot['pilot_trainings'][0]['IR_month'])); 
                            ?>
                        </td>
                        <td>
                            <?php 
                            echo $this->Form->control('IR_frequency', array('class' => 'form-control frequencyM', 'label'=>false, 'value'=>@$pilot['pilot_trainings'][0]['IR_frequency'])); 
                            ?>
                        </td>
                        <td>
                            <div class="input-group date datePicker">
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                            <?php 
                            echo $this->Form->Text('IR_last_completed', array('class' => 'form-control', 'label' => false, 'value'=>!empty(@$pilot['pilot_trainings'][0]['IR_last_completed']) ? date('m/d/Y', strtotime(@$pilot['pilot_trainings'][0]['IR_last_completed'])) : '')); 
                            ?>
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                            <?php
                            //$irNDue = $pilotComp->nextdueProcess(@$pilot['pilot_trainings'][0], 'IR');
                            echo $this->Form->Text('IR_nextdue', array('class'=>'form-control dateNextDue '.$irNDue['colorcls'], 'label'=>false, 'value'=>$irNDue['nextdue'], $irNDue['disabled'])); 
                            echo $irNDue['tooltip'];
                            ?>
                            <input type="hidden" name="IR_nextdue_status" class="ndStatus" value="<?php echo @$pilot['pilot_trainings'][0]['IR_nextdue_status']; ?>">
                            </div>
                        </td>
                    </tr>
                    <?php
                    }

                    if(in_array(@$pilot['pilot_trainings'][0]['IRG_check'], $chk)) {
                    ?>
                    <tr>
                        <td <?php if($type == 'crew'){echo "style='display:none'";} ?>>
                            <?php echo $this->Form->checkbox('IRG_check', array('checked'=>@$pilot['pilot_trainings'][0]['IRG_check'], 'label'=>'')); ?>
                        </td>
                        <td>Initial or Recurrent Ground:</td>
                        <td>
                            <?php 
                            echo $this->Form->control('IRG_month', array('options' => $months, 'empty' => 'Select', 'class' => 'form-control col-md-7 col-xs-12 baseM', 'label' => false, 'value'=>@$pilot['pilot_trainings'][0]['IRG_month'])); 
                            ?>
                        </td>
                        <td>
                            <?php 
                            echo $this->Form->control('IRG_frequency', array('class' => 'form-control frequencyM', 'label'=>false, 'value'=>@$pilot['pilot_trainings'][0]['IRG_frequency'])); 
                            ?>
                        </td>
                        <td>
                            <div class="input-group date datePicker">
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                            <?php 
                            echo $this->Form->Text('IRG_last_completed', array('class' => 'form-control', 'label' => false, 'value'=>!empty(@$pilot['pilot_trainings'][0]['IRG_last_completed']) ? date('m/d/Y', strtotime(@$pilot['pilot_trainings'][0]['IRG_last_completed'])) : '')); 
                            ?>
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                            <?php
                            //$irgNDue = $pilotComp->nextdueProcess(@$pilot['pilot_trainings'][0], 'IRG');
                            echo $this->Form->Text('IRG_nextdue', array('class'=>'form-control dateNextDue '.$irgNDue['colorcls'], 'label'=>false, 'value'=>$irgNDue['nextdue'], $irgNDue['disabled'])); 
                            echo $irgNDue['tooltip'];
                            ?>
                            <input type="hidden" name="IRG_nextdue_status" class="ndStatus" value="<?php echo @$pilot['pilot_trainings'][0]['IRG_nextdue_status']; ?>">
                            </div>
                        </td>
                    </tr>
                    <?php
                    }

                    if(in_array(@$pilot['pilot_trainings'][0]['ICAT_check'], $chk)) {
                    ?>
                    <tr>
                        <td <?php if($type == 'crew'){echo "style='display:none'";} ?>>
                            <?php echo $this->Form->checkbox('ICAT_check', array('checked'=>@$pilot['pilot_trainings'][0]['ICAT_check'], 'label'=>'')); ?>
                        </td>
                        <td>Instructor/Check Airman Training:</td>
                        <td>
                            <?php 
                            echo $this->Form->control('ICAT_month', array('options' => $months, 'empty' => 'Select', 'class' => 'form-control col-md-7 col-xs-12 baseM', 'label' => false, 'value'=>@$pilot['pilot_trainings'][0]['ICAT_month'])); 
                            ?>
                        </td>
                        <td>
                            <?php 
                            echo $this->Form->control('ICAT_frequency', array('class' => 'form-control frequencyM', 'label'=>false, 'value'=>@$pilot['pilot_trainings'][0]['ICAT_frequency'])); 
                            ?>
                        </td>
                        <td>
                            <div class="input-group date datePicker">
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                            <?php 
                            echo $this->Form->Text('ICAT_last_completed', array('class' => 'form-control', 'label' => false, 'value'=>!empty(@$pilot['pilot_trainings'][0]['ICAT_last_completed']) ? date('m/d/Y', strtotime(@$pilot['pilot_trainings'][0]['ICAT_last_completed'])) : '')); 
                            ?>
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                            <?php
                            //$icatNDue = $pilotComp->nextdueProcess(@$pilot['pilot_trainings'][0], 'ICAT');
                            echo $this->Form->Text('ICAT_nextdue', array('class'=>'form-control dateNextDue '.$icatNDue['colorcls'], 'label'=>false, 'value'=>$icatNDue['nextdue'], $icatNDue['disabled']));
                            echo $icatNDue['tooltip'] ;
                            ?>
                            <input type="hidden" name="ICAT_nextdue_status" class="ndStatus" value="<?php echo @$pilot['pilot_trainings'][0]['ICAT_nextdue_status']; ?>">
                            </div>
                        </td>
                    </tr>
                    <?php
                    }

                    if(in_array(@$pilot['pilot_trainings'][0]['LBFT_check'], $chk)) {
                    ?>
                    <tr>
                        <td <?php if($type == 'crew'){echo "style='display:none'";} ?>>
                            <?php echo $this->Form->checkbox('LBFT_check', array('checked'=>@$pilot['pilot_trainings'][0]['LBFT_check'], 'label'=>'')); ?>
                        </td>
                        <td>Lithium Battery Fire Training:</td>
                        <td>
                            <?php 
                            echo $this->Form->control('LBFT_month', array('options' => $months, 'empty' => 'Select', 'class' => 'form-control col-md-7 col-xs-12 baseM', 'label' => false, 'value'=>@$pilot['pilot_trainings'][0]['LBFT_month'])); 
                            ?>
                        </td>
                        <td>
                            <?php 
                            echo $this->Form->control('LBFT_frequency', array('class' => 'form-control frequencyM', 'label'=>false, 'value'=>@$pilot['pilot_trainings'][0]['LBFT_frequency'])); 
                            ?>
                        </td>
                        <td>
                            <div class="input-group date datePicker">
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                            <?php 
                            echo $this->Form->Text('LBFT_last_completed', array('class' => 'form-control', 'label' => false, 'value'=>!empty(@$pilot['pilot_trainings'][0]['LBFT_last_completed']) ? date('m/d/Y', strtotime(@$pilot['pilot_trainings'][0]['LBFT_last_completed'])) : '')); 
                            ?>
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                            <?php
                            //$lbftNDue = $pilotComp->nextdueProcess(@$pilot['pilot_trainings'][0], 'LBFT');
                            echo $this->Form->Text('LBFT_nextdue', array('class'=>'form-control dateNextDue '.$lbftNDue['colorcls'], 'label'=>false, 'value'=>$lbftNDue['nextdue'], $lbftNDue['disabled']));
                            echo $lbftNDue['tooltip']; 
                            ?>
                            <input type="hidden" name="LBFT_nextdue_status" class="ndStatus" value="<?php echo @$pilot['pilot_trainings'][0]['LBFT_nextdue_status']; ?>">
                            </div>
                        </td>
                    </tr>
                    <?php
                    }

                    if(in_array(@$pilot['pilot_trainings'][0]['RVSM_check'], $chk)) {
                    ?>
                    <tr>
                        <td <?php if($type == 'crew'){echo "style='display:none'";} ?>>
                            <?php echo $this->Form->checkbox('RVSM_check', array('checked'=>@$pilot['pilot_trainings'][0]['RVSM_check'], 'label'=>'')); ?>
                        </td>
                        <td>RVSM Training:</td>
                        <td>
                            <?php 
                            echo $this->Form->control('RVSM_month', array('options' => $months, 'empty' => 'Select', 'class' => 'form-control col-md-7 col-xs-12 baseM', 'label' => false, 'value'=>@$pilot['pilot_trainings'][0]['RVSM_month'])); 
                            ?>
                        </td>
                        <td>
                            <?php 
                            echo $this->Form->control('RVSM_frequency', array('class' => 'form-control frequencyM', 'label'=>false, 'value'=>@$pilot['pilot_trainings'][0]['RVSM_frequency'])); 
                            ?>
                        </td>
                        <td>
                            <div class="input-group date datePicker">
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                            <?php 
                            echo $this->Form->Text('RVSM_last_completed', array('class' => 'form-control', 'label' => false, 'value'=>!empty(@$pilot['pilot_trainings'][0]['RVSM_last_completed']) ? date('m/d/Y', strtotime(@$pilot['pilot_trainings'][0]['RVSM_last_completed'])) : '')); 
                            ?>
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                            <?php
                            //$rvsmNDue = $pilotComp->nextdueProcess(@$pilot['pilot_trainings'][0], 'RVSM');
                            echo $this->Form->Text('RVSM_nextdue', array('class'=>'form-control dateNextDue '.$rvsmNDue['colorcls'], 'label'=>false, 'value'=>$rvsmNDue['nextdue'], $rvsmNDue['disabled']));
                            echo $rvsmNDue['tooltip'] ;
                            ?>
                            <input type="hidden" name="RVSM_nextdue_status" class="ndStatus" value="<?php echo @$pilot['pilot_trainings'][0]['RVSM_nextdue_status']; ?>">
                            </div>
                        </td>
                    </tr>
                    <?php
                    }

                    if(in_array(@$pilot['pilot_trainings'][0]['ST_check'], $chk)) {
                    ?>
                    <tr>
                        <td <?php if($type == 'crew'){echo "style='display:none'";} ?>>
                            <?php echo $this->Form->checkbox('ST_check', array('checked'=>@$pilot['pilot_trainings'][0]['ST_check'], 'label'=>'')); ?>
                        </td>
                        <td>Security Training:</td>
                        <td>
                            <?php 
                            echo $this->Form->control('ST_month', array('options' => $months, 'empty' => 'Select', 'class' => 'form-control col-md-7 col-xs-12 baseM', 'label' => false, 'value'=>@$pilot['pilot_trainings'][0]['ST_month'])); 
                            ?>
                        </td>
                        <td>
                            <?php 
                            echo $this->Form->control('ST_frequency', array('class' => 'form-control frequencyM', 'label'=>false, 'value'=>@$pilot['pilot_trainings'][0]['ST_frequency'])); 
                            ?>
                        </td>
                        <td>
                            <div class="input-group date datePicker">
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                            <?php 
                            echo $this->Form->Text('ST_last_completed', array('class' => 'form-control', 'label' => false, 'value'=>!empty(@$pilot['pilot_trainings'][0]['ST_last_completed']) ? date('m/d/Y', strtotime(@$pilot['pilot_trainings'][0]['ST_last_completed'])) : '')); 
                            ?>
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                            <?php
                            //$stNDue = $pilotComp->nextdueProcess(@$pilot['pilot_trainings'][0], 'ST');
                            echo $this->Form->Text('ST_nextdue', array('class'=>'form-control dateNextDue '.$stNDue['colorcls'], 'label'=>false, 'value'=>$stNDue['nextdue'], $stNDue['disabled']));
                            echo $stNDue['tooltip'];
                            ?>
                            <input type="hidden" name="ST_nextdue_status" class="ndStatus" value="<?php echo @$pilot['pilot_trainings'][0]['ST_nextdue_status']; ?>">
                            </div>
                        </td>
                    </tr>
                <?php
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
