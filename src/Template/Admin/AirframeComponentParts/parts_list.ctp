<?php
if(empty($partDropdown)){
	echo '<select name="parent_id" class="form-control col-md-7 col-xs-12 selectpicker" data-show-subtext="true" data-live-search="true" id="parent_id">';
	echo $responce ='<option value="">Select Parent</option>';
	echo '</select>';
} else {
	$responce = '';
	echo '<select name="parent_id" class="form-control col-md-7 col-xs-12 selectpicker" data-show-subtext="true" data-live-search="true" id="parent_id">';
	echo $responce ='<option value="">Select Parent</option>';
	foreach ($partDropdown as $key => $value) {
		echo $responce ='<option value='.$key.'>'.$value.'</option>';
	}
	echo '</select>';
}
?>