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
class CustomerAircraftMaintenanceAppliance extends Entity
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
        'aircraft_id'=>true,
        'a_appliance'=>true,
        'a_serial_no'=>true,
        'a_due_date'=>true,
        'a_cycle_due'=>true,
        'a_landings_due'=>true,
        'a_manufacturer'=>true,
        'a_last_update'=>true,
        'a_time_due'=>true,
        'a_station_weight'=>true,
        'a_model_no'=>true,
        'a_part_no'=>true,
        'a_notes'=>true,
        'status'=>true,
        'added_by'=>true,
        'updated_by'=>true,
        'created_at'=>true,
        'updated_at'=>true,
    ];
}