<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * UserTimeClockHistory Entity
 *
 * @property int $id
 * @property int $user_time_clock_id
 * @property string $title
 * @property int $user_id
 * @property string $description
 * @property \Cake\I18n\DateTime $created
 *
 * @property \App\Model\Entity\UserTimeClock $user_time_clock
 * @property \App\Model\Entity\User $user
 */
class UserTimeClockHistory extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected array $_accessible = [
        'user_time_clock_id' => true,
        'title' => true,
        'user_id' => true,
        'description' => true,
        'created' => true,
        'user_time_clock' => true,
        'user' => true,
    ];
}
