<?php

echo phpinfo();
//$data = openssl_x509_parse(file_get_contents('IntermediateCA.crt'));
/*$data = openssl_x509_parse(file_get_contents('ssl_certificate.crt'));

$validFrom = date('Y-m-d H:i:s', $data['validFrom_time_t']);
$validTo = date('Y-m-d H:i:s', $data['validTo_time_t']);

echo $validFrom . "\n";
echo $validTo . "\n";

echo "<pre>";print_r($data);die;*/

//phpinfo();

//$timestamp = strtotime( date( "Y-m-d 00:00:00", time() + ( 86400 ) ) ) ;
//print_r($timestamp);


function validatecard($number)
 {
    global $type;

    $cardtype = array(
        "visa"       => "/^4[0-9]{12}(?:[0-9]{3})?$/",
        "mastercard" => "/^5[1-5][0-9]{14}$/",
        "amex"       => "/^3[47][0-9]{13}$/",
        "discover"   => "/^6(?:011|5[0-9]{2})[0-9]{12}$/",
    );

    if (preg_match($cardtype['visa'],$number))
    {
	$type= "visa";
        return 'visa';
	
    }
    else if (preg_match($cardtype['mastercard'],$number))
    {
	$type= "mastercard";
        return 'mastercard';
    }
    else if (preg_match($cardtype['amex'],$number))
    {
	$type= "amex";
        return 'amex';
	
    }
    else if (preg_match($cardtype['discover'],$number))
    {
	$type= "discover";
        return 'discover';
    }
    else
    {
        return false;
    } 
 }

//$number = '6011000000000087';
//echo validatecard($number);die;

?>