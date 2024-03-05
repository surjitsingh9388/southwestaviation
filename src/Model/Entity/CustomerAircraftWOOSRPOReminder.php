<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class CustomerAircraftWOOSRPOReminder extends entity{
    protected $_accessible = [
        'wo_osr_po_id'=>true,
        'reminder'=>true,
        'send_reminder_to'=>true,
        'notify_date_time'=>true,
        'status'=>true,
        'added_by'=>true,
        'updated_by'=>true,
        'created_at'=>true,
        'updated_at'=>true,
    ];
}

?>