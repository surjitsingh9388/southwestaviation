<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * UserTimeClockHistories Model
 *
 * @property \App\Model\Table\UserTimeClocksTable&\Cake\ORM\Association\BelongsTo $UserTimeClocks
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\UserTimeClockHistory newEmptyEntity()
 * @method \App\Model\Entity\UserTimeClockHistory newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\UserTimeClockHistory> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UserTimeClockHistory get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\UserTimeClockHistory findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\UserTimeClockHistory patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\UserTimeClockHistory> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\UserTimeClockHistory|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\UserTimeClockHistory saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\UserTimeClockHistory>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\UserTimeClockHistory>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\UserTimeClockHistory>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\UserTimeClockHistory> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\UserTimeClockHistory>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\UserTimeClockHistory>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\UserTimeClockHistory>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\UserTimeClockHistory> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UserTimeClockHistoriesTable extends Table
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

        $this->setTable('user_time_clock_histories');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        /*$this->belongsTo('UserTimeClocks', [
            'foreignKey' => 'user_time_clock_id',
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
            ->integer('user_time_clock_id')
            ->notEmptyString('user_time_clock_id');

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
        //$rules->add($rules->existsIn(['user_time_clock_id'], 'UserTimeClocks'), ['errorField' => 'user_time_clock_id']);
        //$rules->add($rules->existsIn(['user_id'], 'Users'), ['errorField' => 'user_id']);

        return $rules;
    }
}
