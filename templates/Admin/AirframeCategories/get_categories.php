<?php
if(empty($airCats)){
	echo '<select name="airframe_category_id" class="form-control col-md-7 col-xs-12 selectpicker" data-show-subtext="true" data-live-search="true" required="required" id="airframe_category_id">';
	echo $responce ='<option value="">Select Aircraft Category</option>';
	echo '</select>';
} else {
	$responce = '';
	echo '<select name="airframe_category_id" class="form-control col-md-7 col-xs-12 selectpicker" data-show-subtext="true" data-live-search="true" required="required" id="airframe_category_id">';
	echo $responce ='<option value="">Select Aircraft Category</option>';
	foreach ($airCats as $key => $value) {
		echo $responce ='<option value='.$key.'>'.$value.'</option>';
	}
	echo '</select>';
}
?>