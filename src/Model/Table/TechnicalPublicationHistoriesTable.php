<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * TechnicalPublicationHistories Model
 *
 * @property \App\Model\Table\TechnicalPublicationsTable&\Cake\ORM\Association\BelongsTo $TechnicalPublications
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\TechnicalPublicationHistory newEmptyEntity()
 * @method \App\Model\Entity\TechnicalPublicationHistory newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\TechnicalPublicationHistory> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\TechnicalPublicationHistory get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\TechnicalPublicationHistory findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\TechnicalPublicationHistory patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\TechnicalPublicationHistory> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\TechnicalPublicationHistory|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\TechnicalPublicationHistory saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\TechnicalPublicationHistory>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\TechnicalPublicationHistory>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\TechnicalPublicationHistory>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\TechnicalPublicationHistory> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\TechnicalPublicationHistory>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\TechnicalPublicationHistory>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\TechnicalPublicationHistory>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\TechnicalPublicationHistory> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class TechnicalPublicationHistoriesTable extends Table
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

        $this->setTable('technical_publication_histories');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        /*$this->belongsTo('TechnicalPublications', [
            'foreignKey' => 'technical_publication_id',
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
            ->integer('technical_publication_id')
            ->notEmptyString('technical_publication_id');

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
        //$rules->add($rules->existsIn(['technical_publication_id'], 'TechnicalPublications'), ['errorField' => 'technical_publication_id']);
        //$rules->add($rules->existsIn(['user_id'], 'Users'), ['errorField' => 'user_id']);

        return $rules;
    }
}
