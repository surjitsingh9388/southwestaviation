<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class CustomerRepairOrderRate extends entity{
    protected array $_accessible = [
        'customer_id'=>true,
        'repair_order_rates'=>true,
        'labor_discount'=>true,
        'parts_discount'=>true,
        'labor_discount_percentage'=>true,
        'parts_discount_percentage'=>true,
        'part_discount_over_cost'=>true,
        'notes'=>true,
        'show_notes_on_ro_create'=>true,
        'added_by'=>true,
        'updated_by'=>true,
        'created_at'=>true,
        'updated_at'=>true,
    ];
}

?>