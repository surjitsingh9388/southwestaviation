<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Utilization Entity
 *
 * @property int $id
 * @property int $plane_id
 * @property int $airframe_component_id
 * @property int $hours
 * @property int $cycles
 * @property int $rin
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 */
class Utilization extends Entity
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
        'airframe_component_id' => true,
        'hours' => true,
        'cycles' => true,
        'rin' => true,
        'created' => true,
        'modified' => true
    ];
}
