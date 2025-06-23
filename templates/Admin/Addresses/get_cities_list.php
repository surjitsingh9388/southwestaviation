<?php
$response = '';
if(!empty($cities)){
    foreach ($cities as $key => $value) {
        $response .= '<option value='.$key.'>'.$value.'</option>';
    }
} 
echo $response;
?>