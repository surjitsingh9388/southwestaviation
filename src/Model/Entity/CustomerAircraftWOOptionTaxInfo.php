<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class CustomerAircraftWOOptionTaxInfo extends entity{
    protected array $_accessible = [
        'work_order_id'=>true,
        'wo_item_id'=>true,
        'taxable'=>true,
        'tax_method'=>true,
        'tax_rate'=>true,
        'tax_item_labor'=>true,
        'tax_item_oil_analysis'=>true,
        'tax_item_parts'=>true,
        'tax_item_pilot_services'=>true,
        'tax_item_labor_osr'=>true,
        'tax_item_misc_charges'=>true,
        'tax_item_parts_osr'=>true,
        'tax_item_shop_supplies'=>true,
        'tax_item_ship_out'=>true,
        'tax_item_fuel'=>true,
        'tax_item_shipin'=>true,
        'tax_item_tire_disposal'=>true,
        'tax_item_ship_out_osr'=>true,
        'tax_item_cores'=>true,
        'tax_item_shipin_osr'=>true,
        'tax_item_cores_credit'=>true,
        'tax_item_epa_charges'=>true,
        'tax_item_status'=>true,
        'added_by'=>true,
        'updated_by'=>true,
        'created_at'=>true,
        'updated_at'=>true,
    ];
}

?>