<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class UserTimeClock extends Entity
{
    
    protected $_accessible = [
        'user_id' => true,
        'in_time' => true,
        'out_time' => true,
        'status' => true,
        'added_by' => true,
        'updated_by' => true,
        'created_at' => true,
        'updated_at' => true
    ];
}
