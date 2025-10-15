<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * TechnicalPublicationPermission Entity
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $role_id
 * @property int $technical_publication_id
 * @property int $parent_id
 * @property int $action_add
 * @property int $action_edit
 * @property int $action_view
 * @property int $action_delete
 * @property int $updated_by
 * @property \Cake\I18n\DateTime $created
 * @property \Cake\I18n\DateTime|null $modified
 * @property \Cake\I18n\DateTime|null $deleted
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Role $role
 * @property \App\Model\Entity\TechnicalPublication $technical_publication
 * @property \App\Model\Entity\ParentTechnicalPublicationPermission $parent_technical_publication_permission
 * @property \App\Model\Entity\ChildTechnicalPublicationPermission[] $child_technical_publication_permissions
 */
class TechnicalPublicationPermission extends Entity
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
        'user_id' => true,
        'role_id' => true,
        'technical_publication_id' => true,
        'parent_id' => true,
        'action_add' => true,
        'action_edit' => true,
        'action_view' => true,
        'action_delete' => true,
        'updated_by' => true,
        'created' => true,
        'modified' => true,
        'deleted' => true,
        'user' => true,
        'role' => true,
        'technical_publication' => true,
        'parent_technical_publication_permission' => true,
        'child_technical_publication_permissions' => true,
    ];
}
