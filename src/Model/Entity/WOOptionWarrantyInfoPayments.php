<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class WOOptionWarrantyInfoPayment extends entity{
    protected array $_accessible = [
        'warranty_info_id'=>true,
        'warranty_compaines'=>true,
        'company_id'=>true,
        'contact'=>true,
        'currency'=>true,
        'tax_method'=>true,
        'tax_rate1'=>true,
        'tax_rate2'=>true,
        'tax_rate3'=>true,
        'part_pricing_options'=>true,
        'over_cost'=>true,
        'use_labor_rate'=>true,
        'pay_labor'=>true,
        'pay_parts'=>true,
        'pay_shipping'=>true,
        'taxable'=>true,
        'customer_pays_warranty_tax'=>true,
        'specified_labor_rate'=>true,
        'warranty_info_payment_status'=>true,
        'added_by'=>true,
        'updated_by'=>true,
        'created_at'=>true,
        'updated_at'=>true,
    ];
}

?>