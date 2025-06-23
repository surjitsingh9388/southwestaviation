<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class AirframeComponentPartHistory extends Entity
{
    
    protected array $_accessible = [
        'plane_id' => true,
        'airframe_component_part_id' => true,
        'title' => true,
        'user_id' => true,
        'description' => true,
        'created' => true
    ];
}
