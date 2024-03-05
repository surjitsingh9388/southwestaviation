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
class AircraftMaintenanceJetEngine extends Entity
{

    protected $_accessible = [
        'aircraft_id'=>true,
        'engine1_model_no'=>true,
        'engine1_serial_no'=>true,
        'engine1_tt_vacum'=>true,
        'engine1_use_engine'=>true,
        'engine1_tt'=>true,
        'engine1_tso'=>true,
        'engine1_tc'=>true,
        'engine1_tc2'=>true,
        'engine1_tcso'=>true,
        'engine1_hsi_mpi'=>true,
        'engine2_model_no'=>true,
        'engine2_serial_no'=>true,
        'engine2_tt_vacum'=>true,
        'engine2_use_engine'=>true,
        'engine2_tt'=>true,
        'engine2_tso'=>true,
        'engine2_tc'=>true,
        'engine2_tc2'=>true,
        'engine2_tcso'=>true,
        'engine2_hsi_mpi'=>true,
        'engine3_model_no'=>true,
        'engine3_serial_no'=>true,
        'engine3_tt_vacum'=>true,
        'engine3_use_engine'=>true,
        'engine3_tt'=>true,
        'engine3_tso'=>true,
        'engine3_tc'=>true,
        'engine3_tc2'=>true,
        'engine3_tcso'=>true,
        'engine3_hsi_mpi'=>true,
        'added_by'=>true,
        'updated_by'=>true,
        'created_at'=>true,
        'updated_at'=>true,
    ];
}