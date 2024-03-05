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
class InventoryItem extends Entity
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
        'name'=>true,
        'part_number' => true,
        'is_this_item_serialized' => true,
        'accept_install' => true,
        'item_type' => true,
        'capital_equipment' => true,
        'safety_stock_threshold' => true,
        'default_uom' => true,
        'unit_cost' => true,
        'weight' => true,
        'currency' => true,
        'exchange_cost'=>true,
        'rev' => true,
        'manufacturer_id' => true,
        'description' => true,
        'notes' => true,
        'tags' => true,
        'alternate_part_number' => true,
        'in_holdingbox'=>true,
        'item_instock'=>true,
        'exchange_price'=>true,
        'retail_price'=>true,
        'company_purchase_price'=>true,
        'overhauled_cost'=>true,
        'status' => true,
        'added_by'=>true,
        'updated_by'=>true,
        'created' => true,
        'modified' => true,
        'deleted' => true,
    ];
}
