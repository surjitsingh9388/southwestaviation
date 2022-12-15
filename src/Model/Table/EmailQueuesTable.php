<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * EmailQueues Model
 *
 * @method \App\Model\Entity\EmailQueue get($primaryKey, $options = [])
 * @method \App\Model\Entity\EmailQueue newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\EmailQueue[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\EmailQueue|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\EmailQueue patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\EmailQueue[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\EmailQueue findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class EmailQueuesTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('email_queues');
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
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->scalar('email_type')
            ->maxLength('email_type', 100)
            ->requirePresence('email_type', 'create')
            ->notEmpty('email_type');

        $validator
            ->scalar('email_data')
            ->requirePresence('email_data', 'create')
            ->notEmpty('email_data');

        $validator
            ->scalar('status')
            ->requirePresence('status', 'create')
            ->notEmpty('status');

        $validator
            ->integer('updated_by')
            ->requirePresence('updated_by', 'create')
            ->notEmpty('updated_by');

        return $validator;
    }
}
