<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Part Entity
 *
 * @property int $id
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 */
class InventoryShippingOrder extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'shipping_order_number' => true,
        'shipping_order_date' => true,
        'currency' => true,
        'from_address' => true,
        'to_address' => true,
        'account_code'=>true,
        'requestor' => true,
        'vendor' => true,
        'reference' => true,
        'destination' => true,
        'ship_via' => true,
        'special_instructions' => true,
        'shipping_order_tax' => true,
        'shipping_order_tax_percentage' => true,
        'shipping_order_tax_amount' => true,
        'shipping_order_additional_fees' => true,
        'shipping_order_cost_subtotal' => true,
        'shipping_order_cost_total' => true,
        'attention'=>true,
        'shipper'=>true,
        'description'=>true,
        'street1'=>true,
        'street2'=>true,
        'street3'=>true,
        'city'=>true,
        'state'=>true,
        'province'=>true,
        'postal'=>true,
        'country'=>true,
        'shipping_order_status' => true,
        'status'=>true,
        'added_by'=>true,
        'updated_by'=>true,
        'created' => true,
        'modified' => true,
        'deleted' => true,
    ];
}