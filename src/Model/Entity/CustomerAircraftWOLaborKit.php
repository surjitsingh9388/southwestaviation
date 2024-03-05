<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class CustomerAircraftWOLaborKit extends entity{
    protected $_accessible = [
        'user_id'=>true,
        'labor_kit'=>true,
        'status'=>true,
        'added_by'=>true,
        'updated_by'=>true,
        'created_at'=>true,
        'updated_at'=>true,
    ];
}

?>