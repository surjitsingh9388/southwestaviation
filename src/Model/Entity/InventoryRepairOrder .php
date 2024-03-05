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
class InventoryRepairOrder extends Entity
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
        'ro_number' => true,
        'ro_date' => true,
        'currency' => true,
        'bill_to_address' => true,
        'ship_to_address' => true,
        'account_code'=>true,
        'requestor' => true,
        'vendor' => true,
        'reference' => true,
        'contact' => true,
        'ship_via' => true,
        'special_instructions' => true,
        'ro_tax' => true,
        'ro_tax_percentage' => true,
        'ro_tax_amount' => true,
        'ro_shipping' => true,
        'ro_cost_subtotal' => true,
        'ro_cost_total' => true,
        'ro_status' => true,
        'added_by'=>true,
        'updated_by'=>true,
        'created' => true,
        'modified' => true,
        'deleted' => true,
    ];
}