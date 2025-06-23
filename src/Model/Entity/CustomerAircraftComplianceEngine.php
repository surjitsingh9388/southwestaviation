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
class CustomerAircraftComplianceEngine extends Entity
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
        'name'=>true,
        'part_number'=>true,
        'serial_number'=>true,
        'time_limit_of_part'=>true,
        'disable_time_limit'=>true,
        'time_at_install'=>true,
        'ac_tt_at_install'=>true,
        'eng_tt_at_install'=>true,
        'eng_cycle_at_install'=>true,
        'due_date'=>true,
        'use_due_date'=>true,
        'tach_correction'=>true,
        'ac_tach_at_install'=>true,
        'due_hours'=>true,
        'current_eng_tt'=>true,
        'due_next_eng_tt'=>true,
        'time_on_part'=>true,
        'ata_code'=>true,
        'engine'=>true,
        'type'=>true,
        'date_installed'=>true,
        'cycles_limit_of_part'=>true,
        'cycles_at_install'=>true,
        'current_eng_cycle'=>true,
        'due_cycles'=>true,
        'due_next_cycles'=>true,
        'cycles_on_part'=>true,
        'notes'=>true,
        'status'=>true,
        'added_by'=>true,
        'updated_by'=>true,
        'created_at'=>true,
        'updated_at'=>true,
    ];
}