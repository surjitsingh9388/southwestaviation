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
class InventoryPurchaseOrder extends Entity
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
        'po_type'=>true,
        'po_number' => true,
        'po_date' => true,
        'currency' => true,
        'bill_to_address' => true,
        'ship_to_address' => true,
        'account_code'=>true,
        'requestor' => true,
        'vendor' => true,
        'reference' => true,
        'sales_person' => true,
        'ship_via' => true,
        'special_instructions' => true,
        'po_tax' => true,
        'po_tax_percentage' => true,
        'po_tax_amount' => true,
        'po_shipping' => true,
        'po_cost_subtotal' => true,
        'po_cost_total' => true,
        'po_status' => true,
        'status' => true,
        'exchange_status' => true,
        'exchange_note' => true,
        'request'=>true,
        'added_by'=>true,
        'updated_by'=>true,
        'created' => true,
        'modified' => true,
        'deleted' => true,
    ];
}