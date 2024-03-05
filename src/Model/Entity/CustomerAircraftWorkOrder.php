<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class CustomerAircraftWorkOrder extends entity{
    protected $_accessible = [
        'aircraft_id'=>true,
        'wo_customer_id'=>true,
        'order_type'=>true,
        'work_order_no'=>true,
        'wo_phone_no'=>true,
        'work_completed_date'=>true,
        'wo_status'=>true,
        'added_by'=>true,
        'updated_by'=>true,
        'created_at'=>true,
        'updated_at'=>true,
    ];
}

?>