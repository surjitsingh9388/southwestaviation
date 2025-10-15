<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ImportAirframeComponentPartHistories Model
 *
 * @method \App\Model\Entity\ImportAirframeComponentPartHistory newEmptyEntity()
 * @method \App\Model\Entity\ImportAirframeComponentPartHistory newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\ImportAirframeComponentPartHistory> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ImportAirframeComponentPartHistory get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\ImportAirframeComponentPartHistory findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\ImportAirframeComponentPartHistory patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\ImportAirframeComponentPartHistory> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\ImportAirframeComponentPartHistory|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\ImportAirframeComponentPartHistory saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\ImportAirframeComponentPartHistory>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\ImportAirframeComponentPartHistory>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\ImportAirframeComponentPartHistory>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\ImportAirframeComponentPartHistory> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\ImportAirframeComponentPartHistory>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\ImportAirframeComponentPartHistory>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\ImportAirframeComponentPartHistory>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\ImportAirframeComponentPartHistory> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ImportAirframeComponentPartHistoriesTable extends Table
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

        $this->setTable('import_airframe_component_part_histories');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
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
            ->scalar('table_name')
            ->maxLength('table_name', 100)
            ->allowEmptyString('table_name');

        $validator
            ->integer('row_id')
            ->allowEmptyString('row_id');

        $validator
            ->scalar('old_data')
            ->maxLength('old_data', 4294967295)
            ->allowEmptyString('old_data');

        $validator
            ->uuid('batch_id')
            ->allowEmptyString('batch_id');

        return $validator;
    }
}
