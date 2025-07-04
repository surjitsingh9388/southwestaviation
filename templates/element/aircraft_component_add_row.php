<tr>
    <td>
        <?php echo $this->Form->control('plane_id[]', array('options' => $planes, 'empty' => 'Select Aircraft', 'class' => 'form-control col-md-7 col-xs-12 selectpicker aircraft_dropdown', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'planeName_'.$counter, 'value'=>$aircraftId)); ?>
    </td>
    <td>
        <?php echo $this->Form->control('airframe_component_id[]', array('options' => $components, 'empty' => 'Select Aircraft Component', 'class' => 'form-control col-md-7 col-xs-12 selectpicker component_block_id', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id'=>'airframe_component_id_'.$counter, 'value'=>$componentId)); ?>
    </td>
    <td>
        <button type='button' class='btn btn-default delete_aircraft_comp_btn'>Delete</button>
    </td>
</tr>