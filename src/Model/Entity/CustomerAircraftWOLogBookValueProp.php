<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class CustomerAircraftWOLogBookValueProp extends entity{
    protected array $_accessible = [
        'work_order_id'=>true,
        'p_tspoh_l'=>true,
        'p_tt_l'=>true,
        'p_last_prop_balance_l'=>true,
        'p_tspoh_r'=>true,
        'p_tt_r'=>true,
        'p_last_prop_bal_r'=>true,
        'p_oh_date_l'=>true,
        'p_model_no_l'=>true,
        'p_tsn_l'=>true,
        'p_oh_date_r'=>true,
        'p_model_no_r'=>true,
        'p_tsn_r'=>true,
        'p_serial_no_l'=>true,
        'p_tbo'=>true,
        'p_manufacturer'=>true,
        'p_serial_no_r'=>true,
        'status'=>true,
        'added_by'=>true,
        'updated_by'=>true,
        'created_at'=>true,
        'updated_at'=>true,
    ];
}

?>