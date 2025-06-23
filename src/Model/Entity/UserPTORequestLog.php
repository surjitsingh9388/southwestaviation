<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class UserPTORequestsLog extends Entity
{
    
    protected array $_accessible = [
        'pto_requests_id' => true,
        'day_of_week' => true,
        'date_of_day' => true,
        'time_from' => true,
        'time_to' => true,
        'pto_to_use' => true,
        'added_by' => true,
        'updated_by' => true,
        'created_at' => true,
        'updated_at' => true
    ];
}
