<?php
$years  = $pilotComp->getYearsList();
$months = $pilotComp->getMonthsList();
$days   = $pilotComp->getDaysList();
$class  = $pilotComp->getClassList();
?>
<div class="panel-heading">
<?php    
if($type == 'edit') {
    echo '<div class="panel-title">Checking</div>';
} else {
    echo '<div class="panel-title">'.$pilotChecking.' Checking</div>';
}
?>
</div>
<div class="panel-body">
    <div class="table-responsive">
        <table class="table table-borderless table-condensed table-hover">
            <tr>
                <th width="5%" <?php if($type == 'crew'){echo 'style="display:none"';} ?>></th>
                <th <?php if($type == 'crew'){echo 'width="40%"';}else{echo 'width="35%"';} ?>>Aircraft Specific 135.293 (a) 2-3 (b)</th>
                <th width="20%">Base Month</th>
                <th width="15%">Frequency</th>
                <th width="15%">Last Completed</th>
                <th width="10%">Next Due</th>
            </tr>
            <tbody>
                <?php 
                if(in_array(@$pilot['pilot_checkings'][0]['AS_check'], $chk)) {
                ?>
                <tr>
                    <td <?php if($type == 'crew'){echo "style='display:none'";} ?>>
                        <?php echo $this->Form->checkbox('AS_check', array('checked'=>@$pilot['pilot_checkings'][0]['AS_check'], 'label'=>'')); ?>
                    </td>
                    <td>P180</td>
                    <td>
                        <?php 
                        echo $this->Form->control('AS_month', array('options' => $months, 'empty' => 'Select', 'class' => 'form-control col-md-7 col-xs-12 baseM', 'label' => false, 'value'=>@$pilot['pilot_checkings'][0]['AS_month'])); 
                        ?>
                    </td>
                    <td>
                        <?php 
                        echo $this->Form->control('AS_frequency', array('class' => 'form-control frequencyM', 'label'=>false, 'value'=>@$pilot['pilot_checkings'][0]['AS_frequency'])); 
                        ?>  
                    </td>
                    <td>
                        <div class="input-group date datePicker">
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                        <?php 
                        echo $this->Form->Text('AS_last_completed', array('class' => 'form-control', 'label' => false, 'value'=>!empty(@$pilot['pilot_checkings'][0]['AS_last_completed']) ? date('m/d/Y', strtotime(@$pilot['pilot_checkings'][0]['AS_last_completed'])) : '')); 
                        ?>
                        </div>
                    </td>
                    <td>
                       <div class="input-group">
                        <?php
                        //$asNDue = $pilotComp->nextdueProcess(@$pilot['pilot_checkings'][0], 'AS');
                        echo $this->Form->Text('AS_nextdue', array('class'=>'form-control dateNextDue '.$asNDue['colorcls'], 'label'=>false, 'value'=>$asNDue['nextdue'], $asNDue['disabled']));
                        echo $asNDue['tooltip']; 
                        ?>
                        <input type="hidden" name="AS_nextdue_status" class="ndStatus" value="<?php echo @$pilot['pilot_checkings'][0]['AS_nextdue_status']; ?>">
                        </div>
                    </td>
                </tr>
                <?php 
                } 

                if(in_array(@$pilot['pilot_checkings'][0]['ICC_check'], $chk)) {
                ?>
                <tr>
                    <td colspan="5"><b>Instrument Currency Check</b></td>
                </tr>

                <tr>
                    <td <?php if($type == 'crew'){echo "style='display:none'";} ?>>
                        <?php echo $this->Form->checkbox('ICC_check', array('checked'=>@$pilot['pilot_checkings'][0]['ICC_check'], 'label'=>'')); ?>
                    </td>
                    <td>135.297:</td>
                    <td>
                        <?php 
                        echo $this->Form->control('ICC_month', array('options' => $months, 'empty' => 'Select', 'class' => 'form-control col-md-7 col-xs-12 baseM', 'label' => false, 'value'=>@$pilot['pilot_checkings'][0]['ICC_month'])); 
                        ?>
                    </td>
                    <td>
                        <?php 
                        echo $this->Form->control('ICC_frequency', array('class' => 'form-control frequencyM', 'label'=>false, 'value'=>@$pilot['pilot_checkings'][0]['ICC_frequency'])); 
                        ?>
                    </td>
                    <td>
                        <div class="input-group date datePicker">
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                        <?php echo $this->Form->Text('ICC_last_completed', array('class' => 'form-control', 'label' => false, 'value'=>!empty(@$pilot['pilot_checkings'][0]['ICC_last_completed']) ? date('m/d/Y', strtotime(@$pilot['pilot_checkings'][0]['ICC_last_completed'])) : '')); ?>
                        </div>
                    </td>
                    <td>
                        <div class="input-group">
                        <?php
                        //$iccNDue = $pilotComp->nextdueProcess(@$pilot['pilot_checkings'][0], 'ICC');
                        echo $this->Form->Text('ICC_nextdue', array('class'=>'form-control dateNextDue '.$iccNDue['colorcls'], 'label'=>false, 'value'=>$iccNDue['nextdue'], $iccNDue['disabled']));
                        echo $iccNDue['tooltip']; 
                        ?>
                        <input type="hidden" name="ICC_nextdue_status" class="ndStatus" value="<?php echo @$pilot['pilot_checkings'][0]['ICC_nextdue_status']; ?>">
                        </div>
                    </td>
                </tr>
                <?php 
                } 
                ?>

                <?php
                if(in_array(@$pilot['pilot_checkings'][0]['OW_check'], $chk) || in_array(@$pilot['pilot_checkings'][0]['LC_check'], $chk) || in_array(@$pilot['pilot_checkings'][0]['APC_check'], $chk) || in_array(@$pilot['pilot_checkings'][0]['IO_check'], $chk) || in_array(@$pilot['pilot_checkings'][0]['CAO_check'], $chk)) {
                ?>
                    <tr>
                        <td colspan="5"><b>Other Required Checks</b></td>
                    </tr>

                    <?php
                    if(in_array(@$pilot['pilot_checkings'][0]['OW_check'], $chk)) {
                    ?>
                    <tr>
                        <td <?php if($type == 'crew'){echo "style='display:none'";} ?>>
                            <?php echo $this->Form->checkbox('OW_check', array('checked'=>@$pilot['pilot_checkings'][0]['OW_check'], 'label'=>'')); ?>
                        </td>
                        <td>135.293 (a) 1, 4-8 Oral/Written:</td>
                        <td>
                            <?php 
                            echo $this->Form->control('OW_month', array('options' => $months, 'empty' => 'Select', 'class' => 'form-control col-md-7 col-xs-12 baseM', 'label' => false, 'value'=>@$pilot['pilot_checkings'][0]['OW_month'])); 
                            ?>
                        </td>
                        <td>
                            <?php 
                            echo $this->Form->control('OW_frequency', array('class' => 'form-control frequencyM', 'label'=>false, 'value'=>@$pilot['pilot_checkings'][0]['OW_frequency'])); ?>                                            
                        </td>
                        <td>
                            <div class="input-group date datePicker">
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                            <?php 
                            echo $this->Form->Text('OW_last_completed', array('class' => 'form-control', 'label' => false, 'value'=>!empty(@$pilot['pilot_checkings'][0]['OW_last_completed']) ? date('m/d/Y', strtotime(@$pilot['pilot_checkings'][0]['OW_last_completed'])) : '')); 
                            ?>
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                            <?php
                            //$owNDue = $pilotComp->nextdueProcess(@$pilot['pilot_checkings'][0], 'OW');
                            echo $this->Form->Text('OW_nextdue', array('class'=>'form-control dateNextDue '.$owNDue['colorcls'], 'label'=>false, 'value'=>$owNDue['nextdue'], $owNDue['disabled'])); 
                            echo $owNDue['tooltip'];
                            ?>
                            <input type="hidden" name="OW_nextdue_status" class="ndStatus" value="<?php echo @$pilot['pilot_checkings'][0]['OW_nextdue_status']; ?>">
                            </div>
                        </td>
                    </tr>
                    <?php
                    }

                    if(in_array(@$pilot['pilot_checkings'][0]['LC_check'], $chk)) {
                    ?>
                    <tr>
                        <td <?php if($type == 'crew'){echo "style='display:none'";} ?>>
                            <?php echo $this->Form->checkbox('LC_check', array('checked'=>@$pilot['pilot_checkings'][0]['LC_check'], 'label'=>'')); ?>
                        </td>
                        <td>135.299 Line Check:</td>
                        <td>
                            <?php 
                            echo $this->Form->control('LC_month', array('options' => $months, 'empty' => 'Select', 'class' => 'form-control col-md-7 col-xs-12 baseM', 'label' => false, 'value'=>@$pilot['pilot_checkings'][0]['LC_month'])); 
                            ?>
                        </td>
                        <td>
                            <?php 
                            echo $this->Form->control('LC_frequency', array('class' => 'form-control frequencyM', 'label'=>false, 'value'=>@$pilot['pilot_checkings'][0]['LC_frequency'])); 
                            ?>  
                        </td>
                        <td>
                            <div class="input-group date datePicker">
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                            <?php 
                            echo $this->Form->Text('LC_last_completed', array('class' => 'form-control', 'label' => false, 'value'=>!empty(@$pilot['pilot_checkings'][0]['LC_last_completed']) ? date('m/d/Y', strtotime(@$pilot['pilot_checkings'][0]['LC_last_completed'])) : '')); 
                            ?>
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                            <?php
                            //$lcNDue = $pilotComp->nextdueProcess(@$pilot['pilot_checkings'][0], 'LC');
                            echo $this->Form->Text('LC_nextdue', array('class'=>'form-control dateNextDue '.$lcNDue['colorcls'], 'label'=>false, 'value'=>$lcNDue['nextdue'], $lcNDue['disabled']));
                            echo $lcNDue['tooltip'] ;
                            ?>
                            <input type="hidden" name="LC_nextdue_status" class="ndStatus" value="<?php echo @$pilot['pilot_checkings'][0]['LC_nextdue_status']; ?>">
                            </div>
                        </td>
                    </tr>
                    <?php
                    }

                    if(in_array(@$pilot['pilot_checkings'][0]['APC_check'], $chk)) {
                    ?>
                    <tr>
                        <td <?php if($type == 'crew'){echo "style='display:none'";} ?>>
                            <?php echo $this->Form->checkbox('APC_check', array('checked'=>@$pilot['pilot_checkings'][0]['APC_check'], 'label'=>'')); ?>
                        </td>
                        <td>Auto Pilot Check (Single Pilot):</td>
                        <td>
                            <?php 
                            echo $this->Form->control('APC_month', array('options' => $months, 'empty' => 'Select', 'class' => 'form-control col-md-7 col-xs-12 baseM', 'label' => false, 'value'=>@$pilot['pilot_checkings'][0]['APC_month'])); 
                            ?>
                        </td>
                        <td>
                            <?php 
                            echo $this->Form->control('APC_frequency', array('class' => 'form-control frequencyM', 'label'=>false, 'value'=>@$pilot['pilot_checkings'][0]['APC_frequency'])); 
                            ?>
                        </td>
                        <td>
                            <div class="input-group date datePicker">
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                            <?php 
                            echo $this->Form->Text('APC_last_completed', array('class' => 'form-control', 'label' => false, 'value'=>!empty(@$pilot['pilot_checkings'][0]['APC_last_completed']) ? date('m/d/Y', strtotime(@$pilot['pilot_checkings'][0]['APC_last_completed'])) : '')); 
                            ?>
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                            <?php
                            //$apcNDue = $pilotComp->nextdueProcess(@$pilot['pilot_checkings'][0], 'APC');
                            echo $this->Form->Text('APC_nextdue', array('class'=>'form-control dateNextDue '.$apcNDue['colorcls'], 'label'=>false, 'value'=>$apcNDue['nextdue'], $apcNDue['disabled']));
                            echo $apcNDue['tooltip'];
                            ?>
                            <input type="hidden" name="APC_nextdue_status" class="ndStatus" value="<?php echo @$pilot['pilot_checkings'][0]['APC_nextdue_status']; ?>">
                            </div>
                        </td>
                    </tr>
                    <?php
                    }

                    if(in_array(@$pilot['pilot_checkings'][0]['IO_check'], $chk)) {
                    ?>
                    <tr>
                        <td <?php if($type == 'crew'){echo "style='display:none'";} ?>>
                            <?php echo $this->Form->checkbox('IO_check', array('checked'=>@$pilot['pilot_checkings'][0]['IO_check'], 'label'=>'')); ?>
                        </td>
                        <td>Instructor Observation:</td>
                        <td>
                            <?php 
                            echo $this->Form->control('IO_month', array('options' => $months, 'empty' => 'Select', 'class' => 'form-control col-md-7 col-xs-12 baseM', 'label' => false, 'value'=>@$pilot['pilot_checkings'][0]['IO_month'])); 
                            ?>
                        </td>
                        <td>
                            <?php 
                            echo $this->Form->control('IO_frequency', array('class' => 'form-control frequencyM', 'label'=>false, 'value'=>@$pilot['pilot_checkings'][0]['IO_frequency'])); 
                            ?>
                            </td>
                        <td>
                            <div class="input-group date datePicker">
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                            <?php 
                            echo $this->Form->Text('IO_last_completed', array('class' => 'form-control', 'label' => false, 'value'=>!empty(@$pilot['pilot_checkings'][0]['IO_last_completed']) ? date('m/d/Y', strtotime(@$pilot['pilot_checkings'][0]['IO_last_completed'])) : '')); 
                            ?>
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                            <?php
                            //$ioNDue = $pilotComp->nextdueProcess(@$pilot['pilot_checkings'][0], 'IO');
                            echo $this->Form->Text('IO_nextdue', array('class'=>'form-control dateNextDue '.$ioNDue['colorcls'], 'label'=>false, 'value'=>$ioNDue['nextdue'], $ioNDue['disabled']));
                            echo $ioNDue['tooltip'];
                            ?>
                            <input type="hidden" name="IO_nextdue_status" class="ndStatus" value="<?php echo @$pilot['pilot_checkings'][0]['IO_nextdue_status']; ?>">
                            </div>
                        </td>
                    </tr>
                    <?php
                    }

                    if(in_array(@$pilot['pilot_checkings'][0]['CAO_check'], $chk)) {
                    ?>
                    <tr>
                        <td <?php if($type == 'crew'){echo "style='display:none'";} ?>>
                            <?php echo $this->Form->checkbox('CAO_check', array('checked'=>@$pilot['pilot_checkings'][0]['CAO_check'], 'label'=>'')); ?>
                        </td>
                        <td>Check Airman Observation:</td>
                        <td>
                            <?php 
                            echo $this->Form->control('CAO_month', array('options' => $months, 'empty' => 'Select', 'class' => 'form-control col-md-7 col-xs-12 baseM', 'label' => false, 'value'=>@$pilot['pilot_checkings'][0]['CAO_month'])); 
                            ?>
                        </td>
                        <td>
                            <?php 
                            echo $this->Form->control('CAO_frequency', array('class' => 'form-control frequencyM frequency', 'label'=>false, 'value'=>@$pilot['pilot_checkings'][0]['CAO_frequency'])); 
                            ?>  
                        </td>
                        <td>
                            <div class="input-group date datePicker">
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                            <?php 
                            echo $this->Form->Text('CAO_last_completed', array('class' => 'form-control lastComp', 'label' => false, 'value'=>!empty(@$pilot['pilot_checkings'][0]['CAO_last_completed']) ? date('m/d/Y', strtotime(@$pilot['pilot_checkings'][0]['CAO_last_completed'])) : '')); 
                            ?>
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                            <?php
                            //$caoNDue = $pilotComp->nextdueProcess(@$pilot['pilot_checkings'][0], 'CAO');
                            echo $this->Form->Text('CAO_nextdue', array('class'=>'form-control dateNextDue '.$caoNDue['colorcls'], 'label'=>false, 'value'=>$caoNDue['nextdue'], $caoNDue['disabled']));
                            echo $caoNDue['tooltip'];
                            ?>
                            <input type="hidden" name="CAO_nextdue_status" class="ndStatus" value="<?php echo @$pilot['pilot_checkings'][0]['CAO_nextdue_status']; ?>">
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
