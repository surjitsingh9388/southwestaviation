<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ImportAirframeComponentPartHistory Entity
 *
 * @property int $id
 * @property string|null $table_name
 * @property int|null $row_id
 * @property string|null $old_data
 * @property string|null $batch_id
 * @property \Cake\I18n\DateTime|null $created
 */
class ImportAirframeComponentPartHistory extends Entity
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
        'table_name' => true,
        'row_id' => true,
        'old_data' => true,
        'batch_id' => true,
        'created' => true,
    ];
}
