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
class CustomerAircraftMaintenanceAd extends Entity
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
        'ad_no'=>true,
        'ad_recurring_date'=>true,
        'ad_recurring_time'=>true,
        'ad_recurring_landings'=>true,
        'ad_name'=>true,
        'ad_recurring_cycles'=>true,
        'ad_revision_date'=>true,
        'ad_always_recurring'=>true,
        'ad_notes'=>true,
        'status'=>true,
        'added_by'=>true,
        'updated_by'=>true,
        'created_at'=>true,
        'updated_at'=>true,
    ];
}