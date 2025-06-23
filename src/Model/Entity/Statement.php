<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;


class Statement extends Entity
{

    protected array $_accessible = [
        'statement_name' => true,
        'statement_description' => true,
        'status' => true,
        'added_by' => true,
        'updated_by' => true,
        'created_at' => true,
        'updated_at' => true,
    ];
}