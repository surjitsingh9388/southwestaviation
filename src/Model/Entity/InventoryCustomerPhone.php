<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class InventoryCustomerPhone extends Entity
{
    protected array $_accessible = [
        'customer_id'=>true,
        'phone_no'=>true,
        'address' => true,
        'added_by'=>true,
        'created_at' => true,
    ];
}