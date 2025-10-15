<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * TechnicalPublicationHistory Entity
 *
 * @property int $id
 * @property int $technical_publication_id
 * @property string $title
 * @property int $user_id
 * @property string $description
 * @property \Cake\I18n\DateTime $created
 *
 * @property \App\Model\Entity\TechnicalPublication $technical_publication
 * @property \App\Model\Entity\User $user
 */
class TechnicalPublicationHistory extends Entity
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
        'technical_publication_id' => true,
        'title' => true,
        'user_id' => true,
        'description' => true,
        'created' => true,
        'technical_publication' => true,
        'user' => true,
    ];
}
