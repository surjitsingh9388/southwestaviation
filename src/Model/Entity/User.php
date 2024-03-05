<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Auth\DefaultPasswordHasher;

/**
 * User Entity
 *
 * @property int $id
 * @property string $full_name
 * @property string $email
 * @property string $phone
 * @property string $phone_ext
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property int $updated_by
 * @property int $role_id
 * @property string $password
 * @property \Cake\I18n\FrozenTime $last_login
 * @property bool $suspended
 *
 * @property \App\Model\Entity\Role $role
 * @property \App\Model\Entity\Address[] $addresses
 */
class User extends Entity
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
        'title' => true,
        'first_name' => true,
        'middle_name' => true,
        'last_name' => true,
        'suffix' => true,
        'full_name' => true,
        'email' => true,
        'phone' => true,
        'phone_ext' => true,
        //'timezone_id' => true,
        'created' => true,
        'modified' => true,
        'updated_by' => true,
        'role_id' => true,
        'password' => true,
        'last_login' => true,
        'suspended' => true,
        'role' => true,
        'addresses' => true,
        'home_phone' => true,
        'time_clock_code'=>true,
        'certification_code'=>true,
        'is_manager'=>true,
        'direct_manager_id'=>true,
        'employment_date'=>true,
        'salary'=>true,
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        'password'
    ];
    
    /**
     * This function used to encrypt the password value
     *
     * @access protected
     *
     * @param string $password
     * @return string encrypted password string
     */
    protected function _setPassword($password)
    {
        if (strlen($password) > 0) {
            return (new DefaultPasswordHasher)->hash($password);
        }
    }
}
