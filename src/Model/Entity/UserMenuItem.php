<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * UserMenuItem Entity
 *
 * @property int $id
 * @property int $user_id
 * @property int $menu_item_id
 * @property int $parent_id
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property \Cake\I18n\FrozenTime $deleted
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\MenuItem $menu_item
 */
class UserMenuItem extends Entity
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
        'menu_item_id' => true,
        'user_id' => true,
        'role_id' => true,
        'parent_id' => true,
        'action_add' => true,
        'action_edit' => true,
        'action_view' => true,
        'action_delete' => true,
        'action_approve_deny' => true,
        'action_reopen_work_order' => true,
        'action_final_inspections' => true,
        'aircraft_ids' => true,
        'updated_by' => true,
        'created' => true,
        'modified' => true,
        'deleted' => true
    ];
}
