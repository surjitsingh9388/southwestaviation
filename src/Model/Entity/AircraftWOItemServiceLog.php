<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class AircraftWOItemServiceLog extends Entity
{
    protected array $_accessible = [
        'wo_item_id' => true,
        'wo_services_id' => true,
        'login_time' => true,
        'logout_time' => true,
        'hours_worked' => true,
        'currently_on_overtime' => true,
        'added_by' => true,
        'updated_by' => true,
        'created_at' => true,
        'updated_at' => true,
    ];
}
