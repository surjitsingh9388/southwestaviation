<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;


class Setting extends Entity
{

    protected array $_accessible = [
        'logo' => true,
        'office_address' => true,
        'status' => true,
        'added_by' => true,
        'updated_by' => true,
        'created_at' => true,
        'updated_at' => true,
    ];
}
