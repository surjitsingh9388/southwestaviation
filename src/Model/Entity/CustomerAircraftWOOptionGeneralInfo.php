<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class CustomerAircraftWOOptionGeneralInfo extends entity{
    protected array $_accessible = [
        'work_order_id'=>true,
        'wo_item_id'=>true,
        'customer_po'=>true,
        'service_quote'=>true,
        'terms'=>true,
        'disclaimer_for_ro'=>true,
        'min_hour_worked_per_item'=>true,
        'add_hrs_inspection'=>true,
        'overtime_hrs'=>true,
        'date_due'=>true,
        'lead_technician'=>true,
        'sales_reply'=>true,
        'status_notes'=>true,
        'est_invoice'=>true,
        'deposit_notes'=>true,
        'accounting_invoice'=>true,
        'general_info_status'=>true,
        'added_by'=>true,
        'updated_by'=>true,
        'created_at'=>true,
        'updated_at'=>true,
    ];
}

?>