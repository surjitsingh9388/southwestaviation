<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Pilot Entity
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $certificate_number
 * @property string $primary_phone
 * @property string $secondary_phone
 * @property string $emergency_contact
 * @property string $emergency_phone
 * @property string $address
 * @property int $country_id
 * @property int $state_id
 * @property int $city_id
 * @property string $zip_code
 * @property string $status
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\DutyAssignment[] $duty_assignments
 */
class Pilot extends Entity
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
        '*' => true
        /*'name' => true,
        'email' => true,
        'certificate_number' => true,
        'primary_phone' => true,
        'secondary_phone' => true,
        'emergency_contact' => true,
        'emergency_phone' => true,
        'address' => true,
        'country_id' => true,
        'state_id' => true,
        'city_id' => true,
        'zip_code' => true,
        'status' => true,
        'created' => true,
        'modified' => true,
        'duty_assignments' => true,*/
    ];
}
