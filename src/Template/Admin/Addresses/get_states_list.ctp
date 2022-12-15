<?php
$response = '';
if(!empty($states)){
    foreach ($states as $key => $value) {
        $response .= '<option value='.$key.'>'.$value.'</option>';
    }
} 
echo $response;
?>