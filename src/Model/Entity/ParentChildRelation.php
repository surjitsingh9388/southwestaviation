<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ParentChildRelation Entity
 *
 * @property int $id
 * @property int $plane_id
 * @property int $group_id
 * @property int $airframe_component_part_id
 * @property int $parent_id
 */
class ParentChildRelation extends Entity
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
