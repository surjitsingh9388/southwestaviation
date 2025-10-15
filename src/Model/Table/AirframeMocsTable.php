<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AirframeMocs Model
 *
 * @method \App\Model\Entity\AirframeMoc newEmptyEntity()
 * @method \App\Model\Entity\AirframeMoc newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\AirframeMoc> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AirframeMoc get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\AirframeMoc findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\AirframeMoc patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\AirframeMoc> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\AirframeMoc|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\AirframeMoc saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\AirframeMoc>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\AirframeMoc>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\AirframeMoc>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\AirframeMoc> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\AirframeMoc>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\AirframeMoc>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\AirframeMoc>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\AirframeMoc> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AirframeMocsTable extends Table
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

        $this->setTable('airframe_mocs');
        $this->setDisplayField('title');
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
            ->scalar('title')
            ->maxLength('title', 100)
            ->requirePresence('title', 'create')
            ->notEmptyString('title');

        $validator
            ->scalar('status')
            ->notEmptyString('status');

        return $validator;
    }
}
