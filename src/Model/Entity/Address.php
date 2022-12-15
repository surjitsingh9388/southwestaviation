<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Address Entity
 *
 * @property int $id
 * @property string $address_line1
 * @property string $address_line2
 * @property string $zip_code
 * @property int $city
 * @property int $state
 * @property int $country
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property int $user_id
 * @property int $pilot_id
 * @property int $updated_by
 *
 * @property \App\Model\Entity\User $user
 */
class Address extends Entity
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
        'address_line1' => true,
        'address_line2' => true,
        'zip_code' => true,
        'city_id' => true,
        'state_id' => true,
        'country_id' => true,
        'created' => true,
        'modified' => true,
        'user_id' => true,
        'updated_by' => true,
        'deleted' => true,
        'city' => true,
        'state' => true,
        'country' => true,
        'user' => true
    ];
}
