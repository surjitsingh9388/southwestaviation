<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class CustomerAircraftWOMessage extends entity{
    protected $_accessible = [
        'work_order_id'=>true,
        'wo_item_id'=>true,
        'message_to'=>true,
        'message_subject'=>true,
        'message'=>true,
        'message_wo_ro'=>true,
        'is_mark_read'=>true,
        'message_status'=>true,
        'added_by'=>true,
        'updated_by'=>true,
        'created_at'=>true,
        'updated_at'=>true,
    ];
}

?>