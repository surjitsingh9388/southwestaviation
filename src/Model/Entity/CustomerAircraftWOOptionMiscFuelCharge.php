<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class CustomerAircraftWOOptionMiscFuelCharge extends entity{
    protected array $_accessible = [
        'misc_charges_id'=>true,
        'gallon'=>true,
        'price'=>true,
        'added_by'=>true,
        'updated_by'=>true,
        'created_at'=>true,
        'updated_at'=>true,
    ];
}

?>
