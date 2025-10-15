<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * TechnicalPublicationPermissions Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\RolesTable&\Cake\ORM\Association\BelongsTo $Roles
 * @property \App\Model\Table\TechnicalPublicationsTable&\Cake\ORM\Association\BelongsTo $TechnicalPublications
 * @property \App\Model\Table\TechnicalPublicationPermissionsTable&\Cake\ORM\Association\BelongsTo $ParentTechnicalPublicationPermissions
 * @property \App\Model\Table\TechnicalPublicationPermissionsTable&\Cake\ORM\Association\HasMany $ChildTechnicalPublicationPermissions
 *
 * @method \App\Model\Entity\TechnicalPublicationPermission newEmptyEntity()
 * @method \App\Model\Entity\TechnicalPublicationPermission newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\TechnicalPublicationPermission> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\TechnicalPublicationPermission get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\TechnicalPublicationPermission findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\TechnicalPublicationPermission patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\TechnicalPublicationPermission> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\TechnicalPublicationPermission|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\TechnicalPublicationPermission saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\TechnicalPublicationPermission>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\TechnicalPublicationPermission>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\TechnicalPublicationPermission>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\TechnicalPublicationPermission> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\TechnicalPublicationPermission>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\TechnicalPublicationPermission>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\TechnicalPublicationPermission>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\TechnicalPublicationPermission> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class TechnicalPublicationPermissionsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array<string, mixed> $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('technical_publication_permissions');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
        ]);
        /*$this->belongsTo('Roles', [
            'foreignKey' => 'role_id',
        ]);*/
        $this->belongsTo('TechnicalPublications', [
            'foreignKey' => 'technical_publication_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('ParentTechnicalPublicationPermissions', [
            'className' => 'TechnicalPublicationPermissions',
            'foreignKey' => 'parent_id',
        ]);
        $this->hasMany('ChildTechnicalPublicationPermissions', [
            'className' => 'TechnicalPublicationPermissions',
            'foreignKey' => 'parent_id',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('user_id')
            ->allowEmptyString('user_id');

        /*$validator
            ->integer('role_id')
            ->allowEmptyString('role_id');*/

        $validator
            ->integer('technical_publication_id')
            ->notEmptyString('technical_publication_id');

        $validator
            ->integer('parent_id')
            ->notEmptyString('parent_id');

        $validator
            ->integer('action_add')
            ->notEmptyString('action_add');

        $validator
            ->integer('action_edit')
            ->notEmptyString('action_edit');

        $validator
            ->integer('action_view')
            ->notEmptyString('action_view');

        $validator
            ->integer('action_delete')
            ->notEmptyString('action_delete');

        $validator
            ->integer('updated_by')
            ->requirePresence('updated_by', 'create')
            ->notEmptyString('updated_by');

        $validator
            ->dateTime('deleted')
            ->allowEmptyDateTime('deleted');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['user_id'], 'Users'), ['errorField' => 'user_id']);
        //$rules->add($rules->existsIn(['role_id'], 'Roles'), ['errorField' => 'role_id']);
        $rules->add($rules->existsIn(['technical_publication_id'], 'TechnicalPublications'), ['errorField' => 'technical_publication_id']);
        $rules->add($rules->existsIn(['parent_id'], 'ParentTechnicalPublicationPermissions'), ['errorField' => 'parent_id']);

        return $rules;
    }

    public function hasAnyPermissionRecursive(int $userId, int $folderId): bool
    {
        // get direct child folders/files
        $children = $this->TechnicalPublications
            ->find()
            ->select(['TechnicalPublications.id'])
            ->where(['TechnicalPublications.parent_id' => $folderId])
            ->all();

        if ($children->isEmpty()) {
            return false;
        }
        
        foreach ($children as $child) {
            // check if user has any permission on this child
            $perm = $this->find()
                ->where([
                    'TechnicalPublicationPermissions.user_id' => $userId,
                    'TechnicalPublicationPermissions.technical_publication_id' => $child->id,
                    'OR' => [
                        'TechnicalPublicationPermissions.action_view' => 1,
                        'TechnicalPublicationPermissions.action_add' => 1,
                        'TechnicalPublicationPermissions.action_edit' => 1,
                        'TechnicalPublicationPermissions.action_delete' => 1
                    ]
                ])
                ->first();

            if ($perm) {
                return true; // ✅ found a permission
            }

            // recursive check on sub-children
            if ($this->hasAnyPermissionRecursive($userId, $child->id)) {
                return true;
            }
        }

        return false;
    }


}
