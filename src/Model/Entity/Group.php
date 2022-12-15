<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Group Entity
 *
 * @property int $id
 * @property int $plane_id
 * @property int $airframe_component_id
 * @property int $airframe_component_part_id
 * @property string $group_name
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 */
class Group extends Entity
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
        'airframe_component_id' => true,
        'airframe_component_part_id' => true,
        'group_name' => true,
        'created' => true,
        'modified' => true
    ];
}
