<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * UserDepartmentHistories Model
 *
 * @property \App\Model\Table\UserDepartmentsTable&\Cake\ORM\Association\BelongsTo $UserDepartments
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\UserDepartmentHistory newEmptyEntity()
 * @method \App\Model\Entity\UserDepartmentHistory newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\UserDepartmentHistory> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UserDepartmentHistory get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\UserDepartmentHistory findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\UserDepartmentHistory patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\UserDepartmentHistory> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\UserDepartmentHistory|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\UserDepartmentHistory saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\UserDepartmentHistory>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\UserDepartmentHistory>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\UserDepartmentHistory>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\UserDepartmentHistory> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\UserDepartmentHistory>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\UserDepartmentHistory>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\UserDepartmentHistory>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\UserDepartmentHistory> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UserDepartmentHistoriesTable extends Table
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

        $this->setTable('user_department_histories');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        /*$this->belongsTo('UserDepartments', [
            'foreignKey' => 'user_department_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
        ]);*/
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
            ->integer('user_department_id')
            ->notEmptyString('user_department_id');

        $validator
            ->scalar('title')
            ->maxLength('title', 255)
            ->requirePresence('title', 'create')
            ->notEmptyString('title');

        $validator
            ->integer('user_id')
            ->notEmptyString('user_id');

        $validator
            ->scalar('description')
            ->requirePresence('description', 'create')
            ->notEmptyString('description');

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
        //$rules->add($rules->existsIn(['user_department_id'], 'UserDepartments'), ['errorField' => 'user_department_id']);
        //$rules->add($rules->existsIn(['user_id'], 'Users'), ['errorField' => 'user_id']);

        return $rules;
    }
}
