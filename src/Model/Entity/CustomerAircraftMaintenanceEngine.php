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
class CustomerAircraftMaintenanceEngine extends Entity
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
        'aircraft_id'=>true,
        'tsmoh'=>true,
        'ttl'=>true,
        'tt_vac_pump'=>true,
        'tsmoh_r'=>true,
        'tt_r'=>true,
        'tt_vac_pump_r'=>true,
        'oh_date'=>true,
        'model_no'=>true,
        'tsn'=>true,
        'oh_date_r'=>true,
        'model_no_r'=>true,
        'tsn_r'=>true,
        'serial_no'=>true,
        'tbo'=>true,
        'manufacturer'=>true,
        'serial_no_r'=>true,
        'status'=>true,
        'added_by'=>true,
        'updated_by'=>true,
        'created_at'=>true,
        'updated_at'=>true,
    ];
}