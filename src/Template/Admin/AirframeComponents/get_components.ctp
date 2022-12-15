<?php
if(empty($airComps)){
	echo '<select name="airframe_component_id" class="form-control col-md-7 col-xs-12 selectpicker" data-show-subtext="true" data-live-search="true" required="required" id="airframe_component_id">';
	echo $responce ='<option value="">Select Aircraft Component</option>';
	echo '</select>';
} else {
	$responce = '';
	echo '<select name="airframe_component_id" class="form-control col-md-7 col-xs-12 selectpicker" data-show-subtext="true" data-live-search="true" required="required" id="airframe_component_id">';
	echo $responce ='<option value="">Select Aircraft Component</option>';
	foreach ($airComps as $key => $value) {
		echo $responce ='<option value='.$key.'>'.$value.'</option>';
	}
	echo '</select>';
}
?>