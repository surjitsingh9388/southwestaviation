<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class UserPTORequest extends Entity
{
    
    protected array $_accessible = [
        'user_id' => true,
        'manager_id' => true,
        'previous_balance' => true,
        'hours_used_gained' => true,
        'new_balance' => true,
        'pto_requests_status' => true,
        'is_first_paycheck' =>true,
        'is_pto_add'=>true,
        'added_by' => true,
        'updated_by' => true,
        'created_at' => true,
        'updated_at' => true
    ];
}
