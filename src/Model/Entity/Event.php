<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class Event extends Entity
{

    protected array $_accessible = [
        'event_name' => true,
        'event_description'=>true,
        'event_start_date'=>true,
        'event_end_date'=>true,
        'event_start_time'=>true,
        'event_end_time'=>true,
        'added_by'=>true,
        'created_at'=>true,
        'updated_by' => true,
        'updated_at' => true,
    ];
}