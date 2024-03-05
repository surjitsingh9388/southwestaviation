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
class InventoryCustomer extends Entity
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
        'customer_name'=>true,
        'account_number' => true,
        'work_phone' => true,
        'status' => true,
        'use_dealer_price' => true,
        'show_on_invoice' => true,
        'name2' => true,
        'title' => true,
        'home_phone' => true,
        'taxable' => true,
        'address' => true,
        'ship_to_address'=>true,
        'cellular_phone' => true,
        'tax_exempt_expire' => true,
        'address2' => true,
        'ship_to_address2' => true,
        'fax' => true,
        'city' => true,
        'ship_to_city'=>true,
        'pager'=>true,
        'county'=>true,
        'state' => true,
        'zip' => true,
        'ship_to_state' => true,
        'ship_to_zip' => true,
        'email' => true,
        'terms' => true,
        'country' => true,
        'ship_to_country' => true,
        'currency_override' => true,
        'tax_id' => true,
        'country_on_printout' => true,
        'ship_country_on_printout' => true,
        'sales_rep' => true,
        'total_spent' => true,
        'notes_on_wo_create' => true,
        'change_shop_supplier' => true,
        'requires_owner_authorization' => true,
        'country_for_tax_rate' => true,
        'shipping_address_id' => true,
        'notes' => true,
        'added_by'=>true,
        'updated_by'=>true,
        'created' => true,
        'modified' => true,
        'deleted' => true,
    ];
}
