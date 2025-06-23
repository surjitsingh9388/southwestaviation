<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;
use Cake\Datasource\FactoryLocator;
use App\Service\AppService;

/**
 * Users Model
 *
 * @property \App\Model\Table\RolesTable|\Cake\ORM\Association\BelongsTo $Roles
 * @property \App\Model\Table\AddressesTable|\Cake\ORM\Association\HasMany $Addresses
 *
 * @method \App\Model\Entity\User get($primaryKey, $options = [])
 * @method \App\Model\Entity\User newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\User[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\User findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UsersTable extends Table
{    
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config):void
    {
        parent::initialize($config);

        $this->setTable('users');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Roles', [
            'foreignKey' => 'role_id',
            'joinType' => 'INNER'
        ]);
        
        $this->hasMany('Addresses', [
            'foreignKey' => 'user_id',
            'dependent' => true
        ]);
        
        /*$this->belongsTo('Timezones', [
            'foreignKey' => 'timezone_id',
            'joinType' => 'LEFT'
        ]);*/

        $this->hasMany('Pilots', [
            'foreignKey' => 'user_id',
            'dependent' => true
        ]);

        //$this->Planes = FactoryLocator::get('Table')->get('Planes');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator):Validator
    {
        /*$validator
            ->integer('id')
            ->allowEmptyString('id', 'create');*/
        
        $validator
            ->scalar('first_name')
            ->maxLength('first_name', 35)
            ->requirePresence('first_name', 'create')
            ->notEmptyString('first_name');
        
        $validator
            ->scalar('last_name')
            ->maxLength('last_name', 35)
            ->requirePresence('last_name', 'create')
            ->notEmptyString('last_name');

        $validator
            ->scalar('full_name')
            ->maxLength('full_name', 141)
            ->requirePresence('full_name', 'create')
            ->allowEmptyString('full_name');

        $validator
            ->email('email')
            ->requirePresence('email', 'create')
            ->maxLength('email', 50)
            ->add('email', 'unique', ['rule' => 'validateUnique', 'message' => 'Email already registered', 'provider' => 'table']);

        $validator
            ->scalar('phone')
            ->maxLength('phone', 12)
            ->requirePresence('phone', 'create')
            ->notEmptyString('phone');

        $validator
            ->scalar('phone_ext')
            ->maxLength('phone_ext', 10)
            ->requirePresence('phone_ext', 'create')
            ->notEmptyString('phone_ext');

        $validator
            ->scalar('password')
            ->maxLength('password', 255)
            ->requirePresence('password', 'create')
            ->notEmptyString('password');

        $validator
            ->boolean('suspended')
            ->requirePresence('suspended', 'create')
            ->notEmptyString('suspended');

        /*$validator
            ->integer('timezone_id')
            ->requirePresence('timezone_id', 'create')
            ->notEmptyString('timezone_id');

        $validator
            ->integer('time_clock_code')
            ->maxLength('time_clock_code', 4)
            ->requirePresence('time_clock_code', 'create')
            ->allowEmptyString('time_clock_code');

        $validator
            ->integer('certification_code')
            ->maxLength('certification_code', 4)
            ->requirePresence('certification_code', 'create')
            ->allowEmptyString('certification_code');*/

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
        $rules->add($rules->isUnique(['email']));
        $rules->add($rules->existsIn(['role_id'], 'Roles'));

        return $rules;
    }
    
    /**
     * Custom query for users login functionality
     *
     * @param \Cake\ORM\Query $query The query object.
     * @param $options
     * @return returns query object
     */
    public function findAuth(\Cake\ORM\Query $query, array $options)
    {
        /*$query
            ->contain(['Roles' => ['fields' => ['Roles.role_name']]])
            ->where(['Users.suspended' => 0]);*/
        //Removed where clause from above code to display suspended account error message.
        $query
            ->contain(['Roles' => ['fields' => ['Roles.role_name']]]);
        return $query;
    }

    public function beforeSave($options = array())
    {
        $entity = $options->getData('entity');
        $service = new AppService();

        if(!empty($entity->employment_date)) {
            $entity->employment_date = $service->dateFormatBeforeSave($entity->employment_date);
        }

        if(!empty($entity->first_paycheck_date)) {
            $entity->first_paycheck_date = $service->dateFormatBeforeSave($entity->first_paycheck_date);
        }

        return true;
    }
}
