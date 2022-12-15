<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * EmergencyContacts Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\EmergencyContact get($primaryKey, $options = [])
 * @method \App\Model\Entity\EmergencyContact newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\EmergencyContact[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\EmergencyContact|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\EmergencyContact patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\EmergencyContact[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\EmergencyContact findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class EmergencyContactsTable extends Table
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

        $this->setTable('emergency_contacts');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
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
            ->scalar('first_name')
            ->maxLength('first_name', 35)
            //->requirePresence('first_name', 'create');
            ->allowEmpty('first_name');

        $validator
            ->scalar('middle_name')
            ->maxLength('middle_name', 35)
            ->allowEmpty('middle_name');

        $validator
            ->scalar('last_name')
            ->maxLength('last_name', 35)
            //->requirePresence('last_name', 'create')
            ->allowEmpty('last_name');

        $validator
            ->scalar('phone_ext')
            ->maxLength('phone_ext', 10)
            //->requirePresence('phone_ext', 'create')
            ->allowEmpty('phone_ext');

        $validator
            ->scalar('phone')
            ->maxLength('phone', 20)
            //->requirePresence('phone', 'create')
            ->allowEmpty('phone');

        $validator
            ->integer('updated_by')
            ->requirePresence('updated_by', 'create')
            ->notEmpty('updated_by');

        $validator
            ->dateTime('deleted')
            ->allowEmpty('deleted');

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
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }
}
