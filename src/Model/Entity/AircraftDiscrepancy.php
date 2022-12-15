<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * AircraftDiscrepancy Entity
 *
 * @property int $id
 * @property int $plane_id
 * @property \Cake\I18n\FrozenTime $discrepancy_date
 * @property string $discrepancy_time
 * @property string $discrepancy
 * @property string $discovered_by
 * @property string $discovered_cert
 * @property string $discrepancy_mel
 * @property string $mel_category
 * @property string $mel_number
 * @property string $mel_action
 * @property string $deferred_by
 * @property string $deferred_cert
 * @property \Cake\I18n\FrozenTime $mel_repair_by
 * @property string $discrepancy_corrected
 * @property string $corrected_action
 * @property string $corrected_by
 * @property string $corrected_cert
 * @property string $corrected_signature
 * @property \Cake\I18n\FrozenTime $corrected_date
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property int $updated_by
 */
class AircraftDiscrepancy extends Entity
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
        'plane_id' => true,
        'discrepancy_date' => true,
        'discrepancy_time' => true,
        'discrepancy' => true,
        'discovered_by' => true,
        'discovered_cert' => true,
        'discrepancy_mel' => true,
        'mel_category' => true,
        'mel_number' => true,
        'mel_action' => true,
        'deferred_by' => true,
        'deferred_cert' => true,
        'mel_repair_by' => true,
        'discrepancy_corrected' => true,
        'corrected_action' => true,
        'corrected_by' => true,
        'corrected_cert' => true,
        'corrected_signature' => true,
        'corrected_date' => true,
        'created' => true,
        'modified' => true,
        'deleted' => true,
    ];
}
