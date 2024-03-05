<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class CustomerAircraftWOLogBookValueEngine extends entity{
    protected $_accessible = [
        'work_order_id'=>true,
        'tsmoh'=>true,
        'ttl'=>true,
        'tt_vac_pump'=>true,
        'tsmoh_r'=>true,
        'tt_r'=>true,
        'tt_vac_pump_r'=>true,
        'oh_date'=>true,
        'model_no'=>true,
        'tsn'=>true,
        'oh_date_r'=>true,
        'model_no_r'=>true,
        'tsn_r'=>true,
        'serial_no'=>true,
        'tbo'=>true,
        'manufacturer'=>true,
        'serial_no_r'=>true,
        'status'=>true,
        'added_by'=>true,
        'updated_by'=>true,
        'created_at'=>true,
        'updated_at'=>true,
    ];
}

?>