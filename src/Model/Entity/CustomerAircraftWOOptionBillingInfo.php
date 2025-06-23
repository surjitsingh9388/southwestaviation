<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class CustomerAircraftWOOptionBillingInfo extends entity{
    protected array $_accessible = [
        'work_order_id'=>true,
        'wo_item_id'=>true,
        'billing_rate_method'=>true,
        'min_hour_rate'=>true,
        'aircraft_rate_method'=>true,
        'aircraft_rate_hour'=>true,
        'use_special_rate_hrs'=>true,
        'use_special_rate_amount'=>true,
        'default_bill_to_customer'=>true,
        'accounting_type'=>true,
        'customer_currency_format_override'=>true,
        'added_by'=>true,
        'updated_by'=>true,
        'created_at'=>true,
        'updated_at'=>true,
    ];
}

?>