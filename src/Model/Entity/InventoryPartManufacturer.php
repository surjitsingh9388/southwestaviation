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
class InventoryPartManufacturer extends Entity
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
        'name'=>true,
        'street1' => true,
        'street2' => true,
        'city' => true,
        'postal' => true,
        'province'=> true,
        'state' => true,
        'country' => true,
        'accountno' => true,
        'firstname' => true,
        'lastname' => true,
        'primaryemail' => true,
        'secondaryemail' => true,
        'primaryphone' => true,
        'secondaryphone' => true,
        'status' => true,
        'added_by'=>true,
        'updated_by'=>true,
        'created' => true,
        'modified' => true,
        'deleted' => true,
    ];
}
