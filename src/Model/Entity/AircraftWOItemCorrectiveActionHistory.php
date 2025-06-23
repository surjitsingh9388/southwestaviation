<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class AircraftWOItemCorrectiveActionHistory extends Entity
{

    protected array $_accessible = [
        'work_order_id'=>true,
        'wo_item_id'=>true,
        'corrective_action'=>true,
        'status'=>true,
        'added_by'=>true,
        'created_at'=>true,
    ];
}
