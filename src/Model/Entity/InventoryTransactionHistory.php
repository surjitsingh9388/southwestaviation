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
class InventoryTransactionHistory extends Entity
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
        'inventory_id'=>true,
        'purchase_order_id' => true,
        'shipping_order_id' => true,
        'request_id' => true,
        'repair_order_id' => true,
        'from_description' => true,
        'from_location_id' => true,
        'from_status' => true,
        'from_condition' => true,
        'from_item_type' => true,
        'from_cost' => true,
        'to_description' => true,
        'to_location_id' => true,
        'to_status' => true,
        'to_condition' => true,
        'to_item_type' => true,
        'to_cost' => true,
        'type' => true,
        'qty' => true,
        'uom' => true,
        'unit_cost'=>true,
        'reason' => true,
        'tags' => true,
        'vendor_id' => true,
        'account_code' => true,
        'ata_chapter' => true,
        'added_by'=>true,
        'created' => true,
    ];
}
