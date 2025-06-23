<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * AdsbStatusHistory Entity
 *
 * @property int $id
 * @property int $adsb_status_id
 * @property string $title
 * @property int $user_id
 * @property string $description
 * @property \Cake\I18n\FrozenTime $created
 */
class PositionHistory extends Entity
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
        'position_id' => true,
        'title' => true,
        'user_id' => true,
        'description' => true,
        'created' => true,
    ];
}
