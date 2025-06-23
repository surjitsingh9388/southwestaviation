<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class CustomerAircraftWOItemOverview extends entity{
    protected array $_accessible = [
        'wo_item_id'=>true,
        'wo_category'=>true,
        'wo_grouping'=>true,
        'owner_authentication'=>true,
        'warranty'=>true,
        'warranty_claim_no'=>true,
        'log_book_category'=>true,
        'ata_code'=>true,
        'labor_kit_name'=>true,
        'item_is_warranty'=>true,
        'donot_use_inlogbook'=>true,
        'requires_rii'=>true,
        'way_of_billing'=>true,
        'department'=>true,
        'bill_to_customer'=>true,
        'shipping_in'=>true,
        'special_rate_hr'=>true,
        'estimated_hour'=>true,
        'estimated_rate'=>true,
        'flat_rate'=>true,
        'flat_rate_qty'=>true,
        'special_hourly_rate_for_item'=>true,
        'labor_is_taxable'=>true,
        'item_overview_status'=>true,
        'added_by'=>true,
        'updated_by'=>true,
        'created_at'=>true,
        'updated_at'=>true,
    ];
}

?>