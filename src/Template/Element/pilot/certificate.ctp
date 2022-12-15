<?php
$years   = $pilotComp->getYearsList();
$dlyears = $pilotComp->getDLYearsList();
$months  = $pilotComp->getMonthsList();
$days    = $pilotComp->getDaysList();
$class   = $pilotComp->getClassList();
?>
<div class="panel-heading">
<?php 
if($type == 'edit') {
    echo '<div class="panel-title">Pilot Certificates</div>';
} else {
    echo '<div class="panel-title">'.$pilotCert.' Pilot Certificates</div>';
}
?>
</div>
<div class="panel-body">
    <table class="table table-borderless table-condensed table-hover">
        <tr>
            <th width="20%">Certificate Type</th>
            <th width="10%">Under 40 yrs</th>
            <th width="15%">Med Class</th>
            <th width="15%">Limitations</th>
            <th width="15%">Frequency</th>
            <th width="15%">Last Completed</th>
            <th width="10%">Next Due</th>
        </tr>
        <tbody>
            <tr>
                <td>Medical</td>
                <td></td>
                <td>
                    <?php
                    echo $this->Form->control('medical_class', array('options' => $class, 'empty' => 'Select', 'class' => 'form-control col-md-7 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'value'=>!empty($pilot['pilot_certificates'][0]['medical_class']) ? $pilot['pilot_certificates'][0]['medical_class'] : ''));
                    ?>
                </td>
                <td>
                    <?php 
                    echo $this->Form->control('medical_limitation', array('class' => 'form-control', 'label'=>false, 'value'=>$pilot['pilot_certificates'][0]['medical_class'])); 
                    ?>
                </td>
                <td>
                    <?php 
                    echo $this->Form->control('medical_frequency', array('class' => 'form-control frequencyM', 'label'=>false, 'value'=>$pilot['pilot_certificates'][0]['medical_frequency'])); 
                    ?>
                </td>
                <td>
                    <div class="input-group date certDatePicker">
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                    <?php 
                    echo $this->Form->Text('medical_last_completed', array('class' => 'form-control', 'label' => false, 'value'=>!empty($pilot['pilot_certificates'][0]['medical_last_completed']) ? date('m/d/Y', strtotime($pilot['pilot_certificates'][0]['medical_last_completed'])) : '')); 
                    ?>
                    </div>
                    <input type="hidden" name="cert_type" value="months" class="certTypeCls">
                </td>
                <td>
                    <div class="input-group">
                    <?php
                    echo $this->Form->Text('medical_nextdue', array('class'=>'form-control dateNextDue '.$mediNDue['colorcls'], 'label'=>false, 'value'=>$mediNDue['nextdue'], $mediNDue['disabled']));
                    echo $mediNDue['tooltip'];
                    ?>
                    <input type="hidden" name="medical_nextdue_status" class="ndStatus" value="<?php echo $pilot['pilot_certificates'][0]['medical_nextdue_status']; ?>">
                    </div>
                </td>
            </tr>

            <tr>
                <td>Passport</td>
                <td></td>
                <td></td>
                <td></td>
                <td>
                    <?php
                    echo $this->Form->control('passport_frequency', array('options' => $years, 'class' => 'form-control col-md-7 col-xs-12 frequencyM', 'label' => false, 'value'=>!empty($pilot['pilot_certificates'][0]['passport_frequency']) ? $pilot['pilot_certificates'][0]['passport_frequency'] : ''));
                    ?>
                </td>
                <td>
                    <div class="input-group date certDatePicker">
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                    <?php 
                    echo $this->Form->Text('passport_last_completed', array('class' => 'form-control', 'label' => false, 'value'=>!empty($pilot['pilot_certificates'][0]['passport_last_completed']) ? date('m/d/Y', strtotime($pilot['pilot_certificates'][0]['passport_last_completed'])) : '')); 
                    ?>
                    </div>
                    <input type="hidden" name="cert_type" value="years" class="certTypeCls">
                </td>
                <td>
                    <div class="input-group">
                    <?php
                    echo $this->Form->Text('passport_nextdue', array('class'=>'form-control dateNextDue '.$passNDue['colorcls'], 'label'=>false, 'value'=>$passNDue['nextdue'], $passNDue['disabled']));
                    echo $passNDue['tooltip']; 
                    ?>
                    <input type="hidden" name="passport_nextdue_status" class="ndStatus" value="<?php echo $pilot['pilot_certificates'][0]['passport_nextdue_status']; ?>">
                    </div>
                </td>
            </tr>

            <tr>
                <td>Drivers License</td>
                <td></td>
                <td></td>
                <td></td>
                <td>
                    <?php
                    echo $this->Form->control('DL_frequency', array('options' => $dlyears, 'empty' => 'Select', 'class' => 'form-control col-md-7 col-xs-12 frequencyM', 'label' => false, 'value'=>!empty($pilot['pilot_certificates'][0]['DL_frequency']) ? $pilot['pilot_certificates'][0]['DL_frequency'] : ''));
                    ?>
                </td>
                <td>
                    <div class="input-group date certDatePicker">
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                    <?php 
                    echo $this->Form->Text('DL_last_completed', array('class' => 'form-control', 'label' => false, 'value'=>!empty($pilot['pilot_certificates'][0]['DL_last_completed']) ? date('m/d/Y', strtotime($pilot['pilot_certificates'][0]['DL_last_completed'])) : '')); 
                    ?>
                    </div>
                    <input type="hidden" name="cert_type" value="years" class="certTypeCls">
                </td>
                <td>
                    <div class="input-group">
                    <?php
                    echo $this->Form->Text('DL_nextdue', array('class'=>'form-control dateNextDue '.$dlNDue['colorcls'], 'label'=>false, 'value'=>$dlNDue['nextdue'], $dlNDue['disabled']));
                    echo $dlNDue['tooltip']; 
                    ?>
                    <input type="hidden" name="DL_nextdue_status" class="ndStatus" value="<?php echo $pilot['pilot_certificates'][0]['DL_nextdue_status']; ?>">
                    </div>
                </td>
            </tr>

            <tr>
                <td>Temporary Pilot Certificates</td>
                <td></td>
                <td></td>
                <td></td>
                <td>
                    <?php
                    echo $this->Form->control('TPC_frequency', array('options' => $days, 'class' => 'form-control col-md-7 col-xs-12 frequencyM', 'label' => false, 'value'=>!empty($pilot['pilot_certificates'][0]['TPC_frequency']) ? $pilot['pilot_certificates'][0]['TPC_frequency'] : ''));
                    ?>
                </td>
                <td>
                    <div class="input-group date certDatePicker">
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                    <?php 
                    echo $this->Form->Text('TPC_last_completed', array('class' => 'form-control', 'label' => false, 'value'=>!empty($pilot['pilot_certificates'][0]['TPC_last_completed']) ? date('m/d/Y', strtotime($pilot['pilot_certificates'][0]['TPC_last_completed'])) : '')); 
                    ?>
                    </div>
                    <input type="hidden" name="cert_type" value="days" class="certTypeCls">
                </td>
                <td>
                    <div class="input-group">
                    <?php
                    echo $this->Form->Text('TPC_nextdue', array('class'=>'form-control dateNextDue '.$tpcNDue['colorcls'], 'label'=>false, 'value'=>$tpcNDue['nextdue'], $tpcNDue['disabled']));

                    echo $tpcNDue['tooltip']; 
                    ?>
                    <input type="hidden" name="TPC_nextdue_status" class="ndStatus" value="<?php echo $pilot['pilot_certificates'][0]['TPC_nextdue_status']; ?>">
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>