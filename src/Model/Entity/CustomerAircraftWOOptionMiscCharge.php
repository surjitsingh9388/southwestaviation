<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class CustomerAircraftWOOptionMiscCharge extends entity{
    protected $_accessible = [
        'work_order_id'=>true,
        'wo_item_id'=>true,
        'epa_charge'=>true,
        'epa_charge_twin'=>true,
        'epa_charge_amount'=>true,
        'oil_analysis'=>true,
        'oil_analysis_twin'=>true,
        'oil_analysis_amount'=>true,
        'tire_disposal'=>true,
        'tire'=>true,
        'amount_per_tire'=>true,
        'mis_charge'=>true,
        'mis_charge_amount'=>true,
        'pilot_services'=>true,
        'pilot_services_amount'=>true,
        'tax_credit'=>true,
        'tax_credit_amount'=>true,
        'shop_supplies'=>true,
        'shop_supplies_method'=>true,
        'shop_supplies_amount'=>true,
        'percentage_of_labor'=>true,
        'break_off_amount'=>true,
        'break_off_percentage'=>true,
        'above_break_off_percentage'=>true,
        'misc_charge_description_for_invoice'=>true,
        'charge_for_fuel'=>true,
        'fuel_gallons'=>true,
        'fuel_price'=>true,
        'misc_chages_status'=>true,
        'added_by'=>true,
        'updated_by'=>true,
        'created_at'=>true,
        'updated_at'=>true,
    ];
}

?>