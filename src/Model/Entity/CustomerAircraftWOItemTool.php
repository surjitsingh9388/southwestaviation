<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class CustomerAircraftWOItemTool extends entity{
    protected $_accessible = [
        'wo_item_id'=>true,
        'tool_id'=>true,
        'wo_item_tool_name'=>true,
        'equipment_description'=>true,
        'model_no'=>true,
        'serial_no'=>true,
        'date_labeled'=>true,
        'certification'=>true,
        'wo_item_tool_status'=>true,
        'added_by'=>true,
        'updated_by'=>true,
        'created_at'=>true,
        'updated_at'=>true,
    ];
}

?>