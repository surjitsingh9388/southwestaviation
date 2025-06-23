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
class Inventory extends Entity
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
    protected array $_accessible = [
        'install_to'=>true,
        'consume_to'=>true,
        'inventory_item_id'=>true,
        'location_id' => true,
        'serial_no' => true,
        'display_name' => true,
        'cost' => true,
        'currency' => true,
        'exchange_cost' => true,
        'uom' => true,
        'qty' => true,
        'bar_code' => true,
        'account_code' => true,
        'vendor' => true,
        'revision' => true,
        'conditions' => true,
        'ata_chapter' => true,
        'expiration' => true,
        'received' => true,
        'tags' => true,
        'warranty_expire' => true,
        'capital_equipment' => true,
        'notes' => true,
        'months_new' => true,
        'months_overhaul' => true,
        'months_repair' => true,
        'hours_new' => true,
        'hours_overhaul' => true,
        'hours_repair' => true,
        'landings_new' => true,
        'landings_overhaul' => true,
        'landings_repair' => true,
        'cycles_new' => true,
        'cycles_overhaul' => true,
        'cycles_repair' => true,
        'in_holdingbox'=>true,
        'discard_reason'=>true,
        'exchange_price'=>true,
        'retail_price'=>true,
        'company_purchase_price'=>true,
        'overhauled_cost'=>true,
        'tear_down_assest'=>true,
        'status' => true,
        'added_by'=>true,
        'updated_by'=>true,
        'created' => true,
        'modified' => true,
        'deleted' => true,
    ];
}
