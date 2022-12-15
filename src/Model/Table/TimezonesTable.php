<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
//use SoftDelete\Model\Table\SoftDeleteTrait;

/**
 * TimezonesTable Model
 *
 * @property \App\Model\Table\AddressesTable|\Cake\ORM\Association\HasMany $Addresses
 * @property \App\Model\Table\PaymentsTable|\Cake\ORM\Association\HasMany $Payments
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\HasMany $Users
 *
 * @method \App\Model\Entity\Company get($primaryKey, $options = [])
 * @method \App\Model\Entity\Company newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Company[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Company|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Company patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Company[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Company findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class TimezonesTable extends Table
{
    //use SoftDeleteTrait;
     
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('timezones');
        $this->setDisplayField('timezone');
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
            ->scalar('timezone')
            ->maxLength('timezone', 60)
            ->requirePresence('timezone', 'create')
            ->notEmpty('timezone');
        $validator
            ->scalar('description')
            ->maxLength('description', 100)
            ->requirePresence('description', 'create')
            ->notEmpty('description');
        $validator
            ->scalar('code')
            ->maxLength('code', 10)
            ->requirePresence('code', 'create')
            ->notEmpty('code');
        $validator
            ->integer('serial_no')
            ->maxLength('serial_no', 11)
            ->requirePresence('serial_no', 'create')
            ->notEmpty('serial_no');

        $validator
            ->integer('updated_by')
            ->notEmpty('updated_by');
        
        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        return $rules;
    }
}
