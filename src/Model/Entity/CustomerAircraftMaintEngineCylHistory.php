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
class CustomerAircraftMaintEngineCylHistory extends Entity
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
        'eng_cyl_date'=>true,
        'engine1_cylinder_a'=>true,
        'engine1_cylinder_1'=>true,
        'engine1_cylinder_2'=>true,
        'engine1_cylinder_3'=>true,
        'engine1_cylinder_4'=>true,
        'engine1_cylinder_5'=>true,
        'engine1_cylinder_6'=>true,
        'engine2_cylinder_b'=>true,
        'engine2_cylinder_1'=>true,
        'engine2_cylinder_2'=>true,
        'engine2_cylinder_3'=>true,
        'engine2_cylinder_4'=>true,
        'engine2_cylinder_5'=>true,
        'engine2_cylinder_6'=>true,
        'status'=>true,
        'added_by'=>true,
        'updated_by'=>true,
        'created_at'=>true,
        'updated_at'=>true,
    ];
}