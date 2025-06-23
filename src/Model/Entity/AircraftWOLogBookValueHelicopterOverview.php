<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Part Entity
 *
 * @property int $id
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 */
class AircraftWOLogBookValueHelicopterOverview extends Entity
{

    protected array $_accessible = [
        'work_order_id'=>true,
        'actt'=>true,
        'ac_trq_evnt'=>true,
        'rins'=>true,
        'hobbs'=>true,
        'xtube_lndgs'=>true,
        'reg_expires'=>true,
        'actc'=>true,
        'hoist_trq_evnt'=>true,
        'iids'=>true,
        'correction'=>true,
        'gross_weight'=>true,
        'engine1_ttsn'=>true,
        'engine1_tso'=>true,
        'engine1_comp_ttsn'=>true,
        'engine1_comp_ttsn_oh'=>true,
        'engine1_turb_ttsn'=>true,
        'engine1_turb_ttsn_oh'=>true,
        'engine1_gearbox_ttsn'=>true,
        'engine1_gearbox_ttsn_oh'=>true,
        'engine1_ng'=>true,
        'engine1_tcsn'=>true,
        'engine1_cso'=>true,
        'engine1_comp_tcsn'=>true,
        'engine1_correction'=>true,
        'engine1_turb_tcsn'=>true,
        'engine1_grearbox_tcsn'=>true,
        'engine1_np'=>true,
        'engine2_ttsn'=>true,
        'engine2_tso'=>true,
        'engine2_comp_ttsn'=>true,
        'engine2_comp_ttsn_oh'=>true,
        'engine2_turb_ttsn'=>true,
        'engine2_turb_ttsn_oh'=>true,
        'engine2_tcsn'=>true,
        'engine2_cso'=>true,
        'engine2_comp_tcsn'=>true,
        'engine2_correction'=>true,
        'engine2_turb_tcsn'=>true,
        'added_by'=>true,
        'updated_by'=>true,
        'created_at'=>true,
        'updated_at'=>true,
    ];
}