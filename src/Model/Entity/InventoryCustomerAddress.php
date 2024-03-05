<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class InventoryCustomerAddress extends Entity
{
    protected $_accessible = [
        'customer_id'=>true,
        'name'=>true,
        'address' => true,
        'address2' => true,
        'city' => true,
        'country'=>true,
        'state' => true,
        'province'=>true,
        'zip' => true,
        'phone_number' => true,
        'is_billing_address' => true,
        'is_shipping_address' => true,
        'status' => true,
        'added_by'=>true,
        'updated_by'=>true,
        'created' => true,
        'modified' => true,
        'deleted' => true,
    ];
}
