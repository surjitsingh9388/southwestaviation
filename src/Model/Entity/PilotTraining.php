<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * PilotTraining Entity
 *
 * @property int $id
 * @property int $pilot_id
 * @property string $AFT_check
 * @property string $AFT_month
 * @property string $AFT_frequency
 * @property \Cake\I18n\FrozenTime $AFT_last_completed
 * @property string $AFT_nextdue
 * @property string $AFT_nextdue_status
 * @property string $EDT_check
 * @property string $EDT_month
 * @property string $EDT_frequency
 * @property \Cake\I18n\FrozenTime $EDT_last_completed
 * @property string $EDT_nextdue
 * @property string $EDT_nextdue_status
 * @property string $CWOT_check
 * @property string $CWOT_month
 * @property string $CWOT_frequency
 * @property \Cake\I18n\FrozenTime $CWOT_last_completed
 * @property string $CWOT_nextdue
 * @property string $CWOT_nextdue_status
 * @property string $CRM_check
 * @property string $CRM_month
 * @property string $CRM_frequency
 * @property \Cake\I18n\FrozenTime $CRM_last_completed
 * @property string $CRM_nextdue
 * @property string $CRM_nextdue_status
 * @property string $EFB_check
 * @property string $EFB_month
 * @property string $EFB_frequency
 * @property string $EFB_last_completed
 * @property string $EFB_nextdue
 * @property string $EFB_nextdue_status
 * @property string $EGT_check
 * @property string $EGT_month
 * @property string $EGT_frequency
 * @property \Cake\I18n\FrozenTime $EGT_last_completed
 * @property string $EGT_nextdue
 * @property string $EGT_nextdue_status
 * @property string $GIT_check
 * @property string $GIT_month
 * @property string $GIT_frequency
 * @property \Cake\I18n\FrozenTime $GIT_last_completed
 * @property string $GIT_nextdue
 * @property string $GIT_nextdue_status
 * @property string $Hz_check
 * @property string $Hz_month
 * @property string $Hz_frequency
 * @property \Cake\I18n\FrozenTime $Hz_last_completed
 * @property string $Hz_nextdue
 * @property string $Hz_nextdue_status
 * @property string $IR_check
 * @property string $IR_month
 * @property string $IR_frequency
 * @property \Cake\I18n\FrozenTime $IR_last_completed
 * @property string $IR_nextdue
 * @property string $IR_nextdue_status
 * @property string $IRG_check
 * @property string $IRG_month
 * @property string $IRG_frequency
 * @property \Cake\I18n\FrozenTime $IRG_last_completed
 * @property string $IRG_nextdue
 * @property string $IRG_nextdue_status
 * @property string $ICAT_check
 * @property string $ICAT_month
 * @property string $ICAT_frequency
 * @property \Cake\I18n\FrozenTime $ICAT_last_completed
 * @property string $ICAT_nextdue
 * @property string $ICAT_nextdue_status
 * @property string $LBFT_check
 * @property string $LBFT_month
 * @property string $LBFT_frequency
 * @property \Cake\I18n\FrozenTime $LBFT_last_completed
 * @property string $LBFT_nextdue
 * @property string $LBFT_nextdue_status
 * @property string $RVSM_check
 * @property string $RVSM_month
 * @property string $RVSM_frequency
 * @property \Cake\I18n\FrozenTime $RVSM_last_completed
 * @property string $RVSM_nextdue
 * @property string $RVSM_nextdue_status
 * @property string $ST_check
 * @property string $ST_month
 * @property string $ST_frequency
 * @property \Cake\I18n\FrozenTime $ST_last_completed
 * @property string $ST_nextdue
 * @property string $ST_nextdue_status 
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 */
class PilotTraining extends Entity
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
