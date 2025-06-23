<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * PartInstalledTime Entity
 *
 * @property int $id
 * @property int $plane_id
 * @property int $airframe_component_id
 * @property int $airframe_component_part_id
 * @property string $removed_part_number
 * @property string $removed_serial_number
 * @property string $removal_reason
 * @property string $new_months
 * @property int $new_hours
 * @property int $new_landings
 * @property string $overhaul_months
 * @property int $overhaul_hours
 * @property int $overhaul_landings
 * @property string $repair_months
 * @property int $repair_hours
 * @property int $repair_landings
 * @property string $part_type
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 */
class PartInstalledTime extends Entity
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
