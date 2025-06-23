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
class CustomerOTCInfo extends Entity
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
        'customer_id'=>true,
        'terms'=>true,
        'otcinfo_username'=>true,
        'otcinfo_username_disable'=>true,
        'otcinfo_password'=>true,
        'resale'=>true,
        'account'=>true,
        'default_payment_method'=>true,
        'otcinfo_class'=>true,
        'name_on_credit_card'=>true,
        'type_of_credit_card'=>true,
        'credit_card_info'=>true,
        'credit_card_expires'=>true,
        'percentage_off_over_cost'=>true,
        'percentage_off_over_cost_val'=>true,
        'default_shipping_method'=>true,
        'default_ship_to'=>true,
        'total_amount_spent'=>true,
        'max_outstanding_amount'=>true,
        'use_max_outstanding_amount_limit'=>true,
        'show_notes_otc_create'=>true,
        'outstanding_amount_due'=>true,
        'discount_price_level'=>true,
        'use_discount_price_level'=>true,
        'status'=>true,
        'added_by'=>true,
        'updated_by'=>true,
        'created_at'=>true,
        'updated_at'=>true,
    ];
}