<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class UserDepartment extends Entity
{
    
    protected array $_accessible = [
        'department_name' => true,
        'added_by' => true,
        'updated_by' => true,
        'created_at' => true,
        'updated_at' => true
    ];
}
