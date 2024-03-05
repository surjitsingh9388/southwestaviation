<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class CustomerAircraftWOOSRInfoPO extends entity{
    protected $_accessible = [
        'po_no'=>true,
        'vendor_id'=>true,
        'vendor_phone'=>true,
        'date_order_placed'=>true,
        'general_est_arrival_date'=>true,
        'term'=>true,
        'po_status'=>true,
        'created_by'=>true,
        'vendor_contact'=>true,
        'total_shipping_cost'=>true,
        'currency'=>true,
        'ship_to'=>true,
        'full_address_info'=>true,
        'ship_method'=>true,
        'rma_number'=>true,
        'tracking_number'=>true,
        'tracking_number1'=>true,
        'tracking_number2'=>true,
        'tracking_number3'=>true,
        'tracking_number4'=>true,
        'tracking_number5'=>true,
        'additional_tracking_numbers'=>true,
        'service_po_notes'=>true,
        'hide_note_on_printout'=>true,
        'added_by'=>true,
        'updated_by'=>true,
        'created_at'=>true,
        'updated_at'=>true,
    ];
}

?>