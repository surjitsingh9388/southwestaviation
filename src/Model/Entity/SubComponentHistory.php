<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class SubComponentHistory extends Entity
{
    
    protected array $_accessible = [
        'plane_id' => true,
        'sub_component_id' => true,
        'title' => true,
        'user_id' => true,
        'description' => true,
        'created' => true
    ];
}
