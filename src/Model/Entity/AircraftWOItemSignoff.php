<?php
namespace App\Model\Entity;
use Cake\ORM\Entity;

class AircraftWOItemSignoff extends Entity
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
        'wo_item_id'=>true,
        'signoff_category'=>true,
        'inspected_by' => true,
        'inspected_date' => true,
        'status'=>true,
        'added_by'=>true,
        'created_at' => true,
        'updated_by' => true,
        'updated_at' => true
    ];
}