<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * PilotChecking Entity
 *
 * @property int $id
 * @property int $pilot_id
 * @property string $AS_check
 * @property string $AS_month
 * @property string $AS_frequency
 * @property \Cake\I18n\FrozenTime $AS_last_completed
 * @property string $AS_nextdue
 * @property string $AS_nextdue_status
 * @property string $ICC_check
 * @property string $ICC_month
 * @property string $ICC_frequency
 * @property \Cake\I18n\FrozenTime $ICC_last_completed
 * @property string $ICC_nextdue
 * @property string $ICC_nextdue_status
 * @property string $OW_check
 * @property string $OW_month
 * @property string $OW_frequency
 * @property \Cake\I18n\FrozenTime $OW_last_completed
 * @property string $OW_nextdue
 * @property string $OW_nextdue_status
 * @property string $LC_check
 * @property string $LC_month
 * @property string $LC_frequency
 * @property \Cake\I18n\FrozenTime $LC_last_completed
 * @property string $LC_nextdue
 * @property string $LC_nextdue_status
 * @property string $APC_check
 * @property string $APC_month
 * @property string $APC_frequency
 * @property \Cake\I18n\FrozenTime $APC_last_completed
 * @property string $APC_nextdue
 * @property string $APC_nextdue_status
 * @property string $IO_check
 * @property string $IO_month
 * @property string $IO_frequency
 * @property \Cake\I18n\FrozenTime $IO_last_completed
 * @property string $IO_nextdue
 * @property string $IO_nextdue_status
 * @property string $CAO_check
 * @property string $CAO_month
 * @property string $CAO_frequency
 * @property \Cake\I18n\FrozenTime $CAO_last_completed
 * @property string $CAO_nextdue
 * @property string $CAO_nextdue_status
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 */
class PilotChecking extends Entity
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
        '*' => true,
    ];
}
