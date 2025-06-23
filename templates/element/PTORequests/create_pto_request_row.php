<tr>
    <td>
        <input type="hidden" name="pto_request_log_id[]" value="<?php echo @$requestlog['id']; ?>" />
        <?php
            $todaydays = !empty($requestlog['day_of_week']) ? $requestlog['day_of_week'] : date('N');
            $dayoftheweeks = unserialize(DAYOFWEEKS);
            echo $this->Form->control('day_of_week[]', array('options' => $dayoftheweeks, 'empty' => 'Select day of the week', 'class' => 'form-control col-md-8 col-xs-12 selectpicker day_of_week', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'day_of_week_'.$counter, 'value'=>$todaydays)); 
        ?>
    </td>
    <td>
        <div class="input-group date datePicker">
            <?php 
            $date_of_day = !empty($requestlog['date_of_day']) ? date('m-d-Y', strtotime($requestlog['date_of_day'])) : date('m-d-Y');
            echo $this->Form->Text('date_of_day[]', array('class' => 'form-control col-md-8 pto_date_of_day', 'id' => 'pto_date_of_day_'.$counter, 'placeholder' => 'Date', 'label' => false, 'value'=>$date_of_day)); ?>
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
            </span>
        </div>
    </td>
    <td>
        <div class="input-group date datePicker">
            <?php 
            $time_from = !empty($requestlog['time_from']) ? date('H:i', strtotime($requestlog['time_from'])) : '08:00';
            echo $this->Form->Text('time_from[]', array('class' => 'form-control col-md-8 pto_time_from', 'id' => 'pto_time_from_'.$counter, 'placeholder' => 'Time From', 'label' => false, 'value'=>$time_from)); ?>
        </div>
    </td>
    <td>
        <div class="input-group date datePicker">
            <?php 
            $time_to = !empty($requestlog['time_to']) ? date('H:i', strtotime($requestlog['time_to'])) : '17:00';
            echo $this->Form->Text('time_to[]', array('class' => 'form-control col-md-8 pto_time_to', 'id' => 'pto_time_to_'.$counter, 'placeholder' => 'Time To', 'label' => false, 'value'=>$time_to)); ?>
        </div>
    </td>
    <td>
        <?php 
            $ptoToUse = unserialize(PTOTOUSE);
            echo $this->Form->control('pto_to_use[]', array('options' => $ptoToUse, 'empty' => 'Select PTO to use', 'class' => 'form-control col-md-8 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'pto_touse_'.$counter, 'value'=>'8')); 
        ?>
    </td>
    <td>
        <button type='button' class='btn btn-default deleteptobtm'>Delete</button>
    </td>
</tr>