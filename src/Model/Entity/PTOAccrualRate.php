<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class PTOAccrualRate extends Entity
{
    
    protected array $_accessible = [
        'pto_accrual_rate' => true,
        'pto_accrual_rate_value' => true,
        'added_by' => true,
        'updated_by' => true,
        'created_at' => true,
        'updated_at' => true
    ];
}
