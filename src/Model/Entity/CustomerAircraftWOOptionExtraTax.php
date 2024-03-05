<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class CustomerAircraftWOOptionExtraTax extends entity{
    protected $_accessible = [
        'work_order_id'=>true,
        'wo_item_id'=>true,
        'tax_id'=>true,
        'extra_tax_name'=>true,
        'tax_from_labor'=>true,
        'tax_from_shipout'=>true,
        'tax_from_plot_services'=>true,
        'tax_from_parts'=>true,
        'tax_from_shipin'=>true,
        'tax_from_shop_supplies'=>true,
        'tax_from_labor_osr'=>true,
        'tax_from_epa_charges'=>true,
        'tax_from_misc_charges'=>true,
        'tax_from_parts_osr'=>true,
        'tax_from_oil_analysis'=>true,
        'tax_from_fuel'=>true,
        'tax_from_tire_disposal'=>true,
        'always_charge_customer_not_taxable'=>true,
        'tax_percentage'=>true,
        'minimum_amount_to_charge'=>true,
        'maximum_amount_to_charge'=>true,
        'extra_taxes_status'=>true,
        'added_by'=>true,
        'updated_by'=>true,
        'created_at'=>true,
        'updated_at'=>true,
    ];
}

?>