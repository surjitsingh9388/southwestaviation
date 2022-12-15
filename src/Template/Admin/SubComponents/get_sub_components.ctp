<?php
if(empty($subComps)){
	echo '<select name="parent_id" class="form-control col-md-7 col-xs-12 selectpicker" data-show-subtext="true" data-live-search="true" required="required" id="parent_id">';
	echo $responce ='<option value="">Select Sub Component</option>';
	echo '</select>';
} else {
	$responce = '';
	echo '<select name="parent_id" class="form-control col-md-7 col-xs-12 selectpicker" data-show-subtext="true" data-live-search="true" required="required" id="parent_id">';
	echo $responce ='<option value="">Select Sub Component</option>';
	foreach ($subComps as $key => $value) {
		echo $responce ='<option value='.$key.'>'.$value.'</option>';
	}
	echo '</select>';
}
?>