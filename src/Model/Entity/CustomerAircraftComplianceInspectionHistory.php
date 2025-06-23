<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Part Entity
 *
 * @property int $id
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 */
class CustomerAircraftComplianceInspectionHistory extends Entity
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
        'aircraft_inspection_id'=>true,
        'inspection_code'=>true,
        'insp_current_ac_tach'=>true,
        'date_override'=>true,
        'added_by'=>true,
        'created_at'=>true,
        'updated_at'=>true,
    ];
}