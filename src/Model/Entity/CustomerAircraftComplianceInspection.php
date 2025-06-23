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
class CustomerAircraftComplianceInspection extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected array $_accessible = [
        'aircraft_id'=>true,
        'inspection_name'=>true,
        'ata_code'=>true,
        'interval_hours'=>true,
        'interval_months'=>true,
        'interval_cycles'=>true,
        'use_cycles'=>true,
        'type'=>true,
        'current_ac_tach'=>true,
        'tach_correction'=>true,
        'current_ac_tt'=>true,
        'current_ac_landings'=>true,
        'due_hours'=>true,
        'due_date'=>true,
        'due_cycles'=>true,
        'due_next_landings_ac_tt'=>true,
        'notes'=>true,
        'allow_negative_hours'=>true,
        'status'=>true,
        'added_by'=>true,
        'updated_by'=>true,
        'created_at'=>true,
        'updated_at'=>true,
    ];
}