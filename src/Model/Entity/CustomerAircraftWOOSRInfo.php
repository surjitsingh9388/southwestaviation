<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class CustomerAircraftWOOSRInfo extends entity{
    protected $_accessible = [
        'work_order_id'=>true,
        'wo_item_id'=>true,
        'osr_repair_done_by'=>true,
        'is_create_new_ro'=>true,
        'osr_invoice_no'=>true,
        'is_add_to_po'=>true,
        'osr_purchase_order_no'=>true,
        'osr_part_number'=>true,
        'osr_condition'=>true,
        'osr_old_serial_number'=>true,
        'osr_new_serial_number'=>true,
        'osr_labor_charge'=>true,
        'osr_parts_charge'=>true,
        'osr_tax_labor'=>true,
        'osr_tax_parts'=>true,
        'osr_shipping_out'=>true,
        'osr_shipping_in'=>true,
        'osr_vendor_labor_charges'=>true,
        'osr_vendor_part_charges'=>true,
        'osr_description_of_work'=>true,
        'osr_date_due'=>true,
        'osr_inspector_code'=>true,
        'osr_status'=>true,
        'added_by'=>true,
        'updated_by'=>true,
        'created_at'=>true,
        'updated_at'=>true,
    ];
}

?>