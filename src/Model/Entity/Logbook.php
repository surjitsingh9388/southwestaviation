<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Logbook Entity
 *
 * @property int $id
 * @property int $pilot_id
 * @property string $duty_start_hour
 * @property string $duty_start_minute
 * @property string $duty_stop_hour
 * @property string $duty_stop_minute
 * @property string $required_rest
 * @property string $classification
 * @property \Cake\I18n\FrozenTime $selected_date
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 */
class Logbook extends Entity
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
