<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class CustomerAircraftWOItem extends entity{
    protected array $_accessible = [
        'work_order_id'=>true,
        'item_no'=>true,
        'wo_discrepancy'=>true,
        'wo_corrective_action'=>true,
        'wo_created_by'=>true,
        'wo_item_status'=>true,
        'wo_item_position'=>true,
        'item_notes'=>true,
        'show_on_estimate_invoice'=>true,
        'added_by'=>true,
        'updated_by'=>true,
        'created_at'=>true,
        'updated_at'=>true,
    ];
}

?>