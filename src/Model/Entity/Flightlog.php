<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Flightlog Entity
 *
 * @property int $id
 * @property int $user_id
 * @property int $plane_id
 * @property string $trip_id
 * @property string $flight_from
 * @property string $flight_to
 * @property string $leg_type
 * @property int $passengers
 * @property \Cake\I18n\FrozenTime $leg_date
 * @property \Cake\I18n\FrozenTime $leg_start
 * @property \Cake\I18n\FrozenTime $leg_length
 * @property string $notes
 * @property string $is_template
 * @property string $flstatus
 * @property string $status
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 */
class Flightlog extends Entity
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
        '*' => true,
    ];
}
