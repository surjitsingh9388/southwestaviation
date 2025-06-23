<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class CustomerAircraftWOItemService extends entity{
    protected array $_accessible = [
        'wo_item_id'=>true,
        'repair_technician'=>true,
        'service_rate_an_hour'=>true,
        'technician_billing_style'=>true,
        'is_lead_tech_on_item'=>true,
        'currently_on_overtime' => true,
        'estimated_hrs_for_item'=>true,
        'hrs_worked' => true,
        'service_override_hrs' => true,
        'total_hrs_for_tech' => true,
        'total_hrs_for_item' => true,
        'service_overtime_hrs' => true,
        'service_notes'=>true,
        'added_by'=>true,
        'updated_by'=>true,
        'created_at'=>true,
        'updated_at'=>true,
    ];
}

?>