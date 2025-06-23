<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * PilotCertificate Entity
 *
 * @property int $id
 * @property int $pilot_id
 * @property string $medical_class
 * @property string $medical_limitation
 * @property string $medical_frequency
 * @property \Cake\I18n\FrozenTime $medical_last_completed
 * @property string $medical_nextdue
 * @property string $medical_nextdue_status
 * @property string $passport_frequency
 * @property \Cake\I18n\FrozenTime $passport_last_completed
 * @property string $passport_nextdue
 * @property string $passport_nextdue_status
 * @property string $DL_frequency
 * @property \Cake\I18n\FrozenTime $DL_last_completed
 * @property string $DL_nextdue
 * @property string $DL_nextdue_status
 * @property string $TPC_frequency
 * @property \Cake\I18n\FrozenTime $TPC_last_completed
 * @property string $TPC_nextdue
 * @property string $TPC_nextdue_status
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 */
class PilotCertificate extends Entity
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
        '*' => true,
    ];
}
