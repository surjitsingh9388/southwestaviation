<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class CustomerAircraftWOOptionWarrantyInfo extends entity{
    protected array $_accessible = [
        'work_order_id'=>true,
        'wo_item_id'=>true,
        'warranty_info_status'=>true,
        'added_by'=>true,
        'updated_by'=>true,
        'created_at'=>true,
        'updated_at'=>true,
    ];
}

?>