<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * CrewDetail Entity
 *
 * @property int $id
 * @property int $plane_id
 * @property int $flightlog_id
 * @property string $trip_id
 * @property string $crew_member
 * @property string $member_type
 * @property string $pilot_flying    
 * @property \Cake\I18n\FrozenTime $duty_start_date
 * @property \Cake\I18n\FrozenTime $duty_on
 * @property \Cake\I18n\FrozenTime $duty_off
 * @property string $required_rest
 * @property string $legs_apply
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 */
class CrewDetail extends Entity
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
        'id' => true,
        'plane_id' => true,
        'flightlog_id' => true,
        'trip_id' => true,
        'crew_member' => true,
        'member_type' => true,
        'pilot_flying' => true,
        'duty_start_date' => true,
        'duty_on' => true,
        'duty_off' => true,
        'required_rest' => true,
        'legs_apply' => true,
        'created' => true,
        'modified' => true,
    ];
}