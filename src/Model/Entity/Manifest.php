<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Manifest Entity
 *
 * @property int $id
 * @property int $pilot_id
 * @property int $flightlog_id
 * @property string $trip_id
 * @property int $max_weight
 * @property int $actual_weight
 * @property int $forward_cg
 * @property int $actual_cg
 * @property int $aft_cg
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Pilot $pilot
 */
class Manifest extends Entity
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