<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class InventoryTool extends Entity
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
        'tool_name'=>true,
        'equipment_description' => true,
        'manufacturer' => true,
        'model_no' => true,
        'serial_no' => true,
        'vendor_id' => true,
        'calibration_schedule' => true,
        'cost' => true,
        'calibration_date' => true,
        'due_date' => true,
        'date_labeled' => true,
        'certification' => true,
        'general_location' => true,
        'tool_location' => true,
        'company_location' => true,
        'calibration_status' => true,
        'date_purchased' => true,
        'tool_status' => true,
        'tool_notes'=> true,
        'added_by'=>true,
        'updated_by'=>true,
        'created_at' => true,
        'updated_at' => true,
    ];
}
