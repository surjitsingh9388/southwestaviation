<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * AirframeCategory Entity
 *
 * @property int $id
 * @property string $plane_id
 * @property string $category_name
 * @property string $serial_number
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property int $updated_by
 */
class AirframeCategory extends Entity
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
        'plane_id' => true,
        'category_name' => true,
        'serial_number' => true,
        'created' => true,
        'modified' => true,
        'updated_by' => true,
    ];
}
