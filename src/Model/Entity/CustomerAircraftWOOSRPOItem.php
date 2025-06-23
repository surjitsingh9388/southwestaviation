<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class CustomerAircraftWOOSRPOItem extends entity{
    protected array $_accessible = [
        'osr_po_id'=>true,
        'destination_id'=>true,
        'destination'=>true,
        'item_no'=>true,
        'part_number'=>true,
        'old_serial_number'=>true,
        'mark_arrived'=>true,
        'labor_cost'=>true,
        'part_cost'=>true,
        'ship_out'=>true,
        'ship_in'=>true,
        'labor_charge'=>true,
        'parts_charge'=>true,
        'tax_labor'=>true,
        'tax_part'=>true,
        'new_part_sn'=>true,
        'owner'=>true,
        'mparts_cost'=>true,
        'mparts_retail'=>true,
        'part_location'=>true,
        'po_condition'=>true,
        'shelf_life'=>true,
        'warranty_expires'=>true,
        'epa_charges'=>true,
        'description_work_requested'=>true,
        'lot_no'=>true,
        'print_label_when_applicable'=>true,
        'estimated_arrival_date'=>true,
        'po_item_status'=>true,
        'added_by'=>true,
        'updated_by'=>true,
        'created_at'=>true,
        'updated_at'=>true,
    ];
}

?>