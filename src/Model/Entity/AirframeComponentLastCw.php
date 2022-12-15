<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * AirframeComponentLastCw Entity
 *
 * @property int $id
 * @property int $plane_id
 * @property int $airframe_component_id
 * @property int $airframe_category_id
 * @property int $airframe_component_part_id
 * @property \Cake\I18n\FrozenTime $last_cw_date
 * @property int $last_cw_hrs
 * @property int $last_cw_afl
 * @property int $last_cw_msc
 * @property \Cake\I18n\FrozenTime $next_due_date
 * @property int $next_due_hrs
 * @property int $next_due_afl
 * @property int $next_due_msc
 * @property int $tolerance_mos
 * @property int $tolerance_days
 * @property int $tolerance_hrs
 * @property int $tolerance_afl
 * @property int $recurring_mos
 * @property int $recurring_days
 * @property int $recurring_hrs
 * @property int $recurring_afl
 * @property int $threshold_mos
 * @property int $threshold_days
 * @property int $threshold_hrs
 * @property int $threshold_afl
 * @property int $alert_days
 * @property int $alert_hrs
 * @property int $alert_afl
 * @property int $required_frequency_mos
 * @property int $required_frequency_days
 * @property int $required_frequency_hrs
 * @property int $required_frequency_afl
 * @property int $adjustment_mos
 * @property int $adjustment_days
 * @property int $adjustment_hrs
 * @property int $adjustment_afl
 * @property string $last_revised_by
 * @property string $last_reported_by
 * @property string $is_recThres
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property int $updated_by
 */
class AirframeComponentLastCw extends Entity
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
        'airframe_component_id' => true,
        'airframe_category_id' => true,
        'airframe_component_part_id' => true,
        'last_cw_date' => true,
        'last_cw_hrs' => true,
        'last_cw_afl' => true,
        'last_cw_msc' => true,
        'next_due_date' => true,
        'next_due_hrs' => true,
        'next_due_afl' => true,
        'next_due_msc' => true,
        'tolerance_mos' => true,
        'tolerance_days' => true,
        'tolerance_hrs' => true,
        'tolerance_afl' => true,
        'alert_days' => true,
        'alert_hrs' => true,
        'alert_afl' => true,
        'recurring_mos' => true,
        'recurring_days' => true,
        'recurring_hrs' => true,
        'recurring_afl' => true,
        'threshold_mos' => true,
        'threshold_days' => true,
        'threshold_hrs' => true,
        'threshold_afl' => true,
        'required_frequency_mos' => true,
        'required_frequency_days' => true,
        'required_frequency_hrs' => true,
        'required_frequency_afl' => true,
        'adjustment_mos' => true,
        'adjustment_days' => true,
        'adjustment_hrs' => true,
        'adjustment_afl' =>true,
        'last_revised_by' =>true,
        'last_reported_by' => true,
        'is_recThres' => true,
        'override' => true,
        'eom' => true,
        'created' => true,
        'modified' => true,
        'deleted' => true,
    ];
}
