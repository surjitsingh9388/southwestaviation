<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * FlightLegDetail Entity
 *
 * @property int $id
 * @property int $pilot_id
 * @property string $start_hour
 * @property string $start_minute
 * @property string $leg_hour
 * @property string $leg_minute
 * @property string $night_flt_hour
 * @property string $night_flt_minute
 * @property string $ifr_flight_hour
 * @property string $ifr_flight_minute
 * @property string $approaches
 * @property string $landings
 * @property string $duty_position
 * @property string $part_135
 * @property \Cake\I18n\FrozenTime $selected_date
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 */
class FlightLegDetail extends Entity
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
    protected $_accessible = [
        '*' => true,
    ];
}
