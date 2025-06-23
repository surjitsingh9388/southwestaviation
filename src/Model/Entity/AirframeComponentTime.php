<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * AirframeComponentTime Entity
 *
 * @property int $id
 * @property int $plane_id
 * @property int $user_id
 * @property int $airframe_component_id
 * @property int $flightlog_id
 * @property \Cake\I18n\FrozenTime $log_date
 * @property int $hours
 * @property int $cycles
 * @property int $hours_accrued
 * @property int $cycles_accrued
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property int $updated_by
 */
class AirframeComponentTime extends Entity
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
        'plane_id' => true,
        'user_id' => true,
        'airframe_component_id' => true,
        'flightlog_id' => true,
        'log_date' => true,
        'hours' => true,
        'cycles' => true,
        'hours_accrued' => true,
        'cycles_accrued' => true,
        'created' => true,
        'modified' => true,
        'updated_by' => true,
    ];
}
