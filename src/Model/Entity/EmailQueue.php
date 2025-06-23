<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * EmailQueue Entity
 *
 * @property int $id
 * @property string $email_type
 * @property string $email_data
 * @property string $status
 * @property int $updated_by
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 */
class EmailQueue extends Entity
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
        'email_type' => true,
        'email_data' => true,
        'status' => true,
        'updated_by' => true,
        'created' => true,
        'modified' => true
    ];
}
