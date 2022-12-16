<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Part Entity
 *
 * @property int $id
 * @property string $plane_name
 * @property string $plane_type
 * @property string $plane_code
 * @property string $manufacturered_by
 * @property string $manufacturered_on
 * @property string $plane_serial_number
 * @property string $federal_aviation_regulation
 * @property \Cake\I18n\FrozenTime $airworthiness_date
 * @property int $hours
 * @property int $cycles
 * @property string $address
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 */
class Part extends Entity
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
        'part_number' => true,
        'serial_number' => true,
        'description' => true,
        'part_classification' => true,
        'lot_number' => true,
        'qty' => true,
        'owner_of_part' => true,
        'location' => true,
        'conditions' => true,
        'cost' => true,
        'retail' => true,
        'date_received' => true,
        'vendor' => true,
        'warranty_expires' => true,
        'invoice' => true,
        'purchase_order' => true,
        'lot' => true,
        'shelf_life' => true,
        'approved_by' => true,
        'created' => true,
        'modified' => true,
        'deleted' => true,
    ];
}
