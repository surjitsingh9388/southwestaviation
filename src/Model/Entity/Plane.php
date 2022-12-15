<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Plane Entity
 *
 * @property int $id
 * @property string $plane_name
 * @property string $plane_type
 * @property string $plane_code
 * @property string $manufacturered_by
 * @property string $manufacturered_on
 * @property string $plane_serial_number
 * @property string $federal_aviation_regulation
 * @property \Cake\I18n\FrozenTime $airworthiness_date
 * @property int $hours
 * @property int $cycles
 * @property string $address
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 */
class Plane extends Entity
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
        'plane_name' => true,
        'plane_type' => true,
        'plane_code' => true,
        'manufacturered_by' => true,
        'manufacturered_on' => true,
        'plane_serial_number' => true,
        'federal_aviation_regulation' => true,
        'airworthiness_date' => true,
        'hours' => true,
        'cycles' => true,
        'address' => true,
        'status' => true,
        'order_type' => true,
        'created' => true,
        'modified' => true,
        'deleted' => true,
    ];
}
