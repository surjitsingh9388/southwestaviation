<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * FlightlogDetail Entity
 *
 * @property int $id
 * @property int $user_id
 * @property int $plane_id
 * @property int $flightlog_id
 * @property string $trip_id
 * @property string $takeoff_timezone
 * @property string $landing_timezone
 * @property \Cake\I18n\FrozenTime $taxi_out
 * @property \Cake\I18n\FrozenTime $taxi_off
 * @property \Cake\I18n\FrozenTime $landing
 * @property \Cake\I18n\FrozenTime $taxi_in
 * @property int $takeoff_hobbs
 * @property int $landing_hobbs
 * @property string $approaches
 * @property string $approach_type
 * @property string $takeoff_type
 * @property string $landing_type
 * @property int $night_time
 * @property int $inst
 * @property string $fuel_on_board
 * @property int $fuel_purchased
 * @property string $fuel_type
 * @property string $ending_fuel
 * @property string $fuel_total
 * @property int $fuel_cost
 * @property string $fuel_burn
 * @property string $apu_time
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 */
class FlightlogDetail extends Entity
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
        '*' => true
    ];
}
