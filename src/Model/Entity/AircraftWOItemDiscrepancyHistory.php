<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class AircraftWOItemDiscrepancyHistory extends Entity
{

    protected $_accessible = [
        'work_order_id'=>true,
        'wo_item_id'=>true,
        'discrepancy'=>true,
        'status'=>true,
        'added_by'=>true,
        'created_at'=>true,
    ];
}