<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class AirframeComponentHistory extends Entity
{
    
    protected array $_accessible = [
        'plane_id' => true,
        'airframe_component_id' => true,
        'title' => true,
        'user_id' => true,
        'description' => true,
        'created' => true
    ];
}
