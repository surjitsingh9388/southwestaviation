<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * AirframeComponent Entity
 *
 * @property int $id
 * @property int $plane_id
 * @property string $log_book
 * @property int $position
 * @property string $description
 * @property string $serial_no
 * @property string $log_date
 * @property int $hours
 * @property int $cycles
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 */
class AirframeComponent extends Entity
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
        'plane_id'    => true,
        'log_book'    => true,
        'position'    => true,
        'description' => true,
        'serial_no'   => true,
        'log_date'    => true,
        'hours'       => true,
        'cycles'      => true,
        'created'     => true,
        'modified'    => true,
        'deleted'     => true,
    ];
}
