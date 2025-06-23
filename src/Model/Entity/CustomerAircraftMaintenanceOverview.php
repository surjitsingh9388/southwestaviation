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
class CustomerAircraftMaintenanceOverview extends Entity
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
        'next_annual'=>true,
        'next_elt_date'=>true,
        'next_corrosion'=>true,
        'next_o2_bottle'=>true,
        'next_far_91_411'=>true,
        'reg_expires'=>true,
        'next_far_91_413'=>true,
        'warranty_date'=>true,
        'ac_battery_date'=>true,
        'last_oil_change_date'=>true,
        'last_oil_change_time'=>true,
        'tach_is_flight_time'=>true,
        'current_ac_tt'=>true,
        'current_ac_tach'=>true,
        'hobbs'=>true,
        'use_hobbs'=>true,
        'airswitch'=>true,
        'tach_correction'=>true,
        '50_hour'=>true,
        '100_hour'=>true,
        'heater_hobbs'=>true,
        'airframe_lndgs'=>true,
        'apu_make'=>true,
        'apu_model'=>true,
        'apu_serial'=>true,
        'apu_tso'=>true,
        'apu_tc'=>true,
        'apu_tcso'=>true,
        'apu_tsn'=>true,
        'airframe_tt'=>true,
        'gross_weight'=>true,
        'general_specs_info'=>true,
        'status'=>true,
        'added_by'=>true,
        'updated_by'=>true,
        'created_at'=>true,
        'updated_at'=>true,
    ];
}