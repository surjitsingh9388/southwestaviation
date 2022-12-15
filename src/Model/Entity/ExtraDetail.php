<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ExtraDetail Entity
 *
 * @property int $id
 * @property int $plane_id
 * @property \Cake\I18n\FrozenTime $date
 * @property string $reference
 * @property string $notes
 */
class ExtraDetail extends Entity
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
        'airframe_component_time_id' => true,
        'history_ids' => true,
        'date' => true,
        'reference' => true,
        'notes' => true
    ];
}
