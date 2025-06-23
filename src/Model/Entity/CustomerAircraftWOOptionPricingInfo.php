<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class CustomerAircraftWOOptionPricingInfo extends entity{
    protected array $_accessible = [
        'work_order_id'=>true,
        'wo_item_id'=>true,
        'labor_discount'=>true,
        'labor_discount_percentage'=>true,
        'flat_discount_amount'=>true,
        'parts_user_dealer_price'=>true,
        'parts_discount'=>true,
        'parts_discount_percentage'=>true,
        'parts_flat_discount_amount'=>true,
        'discount_over_cost'=>true,
        'donot_use_markup_formula'=>true,
        'donot_charge_shipping'=>true,
        'is_internal_bill_at_cost'=>true,
        'pricing_info_status'=>true,
        'added_by'=>true,
        'updated_by'=>true,
        'created_at'=>true,
        'updated_at'=>true,
    ];
}

?>