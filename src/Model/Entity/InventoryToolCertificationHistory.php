<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class InventoryToolCertificationHistory extends Entity
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
        'tool_id'=>true,
        'date_sent_out' => true,
        'date_received_back' => true,
        'date_of_calibration' => true,
        'sent_to' => true,
        'was_in_calibration' => true,
        'adjustment_needed' => true,
        'notes' => true,
        'status'=> true,
        'added_by'=>true,
        'updated_by'=>true,
        'created_at' => true,
        'updated_at' => true,
    ];
}
