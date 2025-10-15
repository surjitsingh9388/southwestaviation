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
        
        if(!empty($entity->id)){
            $usersModel = FactoryLocator::get('Table')->get('Users');
            $addressesModel = FactoryLocator::get('Table')->get('Addresses');

            $users = $usersModel->get($entity->id);
            $addresses = $addressesModel->find()->where(['user_id'=>$entity->id])->select($this->Addresses)->first();

            $userHistoriesModel = FactoryLocator::get('Table')->get('UserHistories');
            
            $userHistory = $userHistoriesModel->newEmptyEntity();
            
            if(!empty($users->employment_date)) {
                $users->employment_date = $service->dateFormatBeforeSave($users->employment_date);
            }

            if(!empty($users->first_paycheck_date)) {
                $users->first_paycheck_date = $service->dateFormatBeforeSave($users->first_paycheck_date);
            }

            $userHistory->user_tbl_id = $entity->id;

            $userHistory->title = 'User '.$users->full_name.' was updated.';
            
            if(!empty($users->modified)){
                $modified_from = str_replace('-', '/', $users->modified);
                $modified_from = date("Y-m-d h:i A", strtotime($modified_from));
            }else{
                $modified_from = '';
            }

            if(!empty($entity->modified)){
                $modified_to = str_replace('-', '/', $entity->modified);
                $modified_to = date("Y-m-d h:i A", strtotime($modified_to));
            }else{
                $modified_to = '';
            }

            $description = '';
            if($entity->title != $users->title){
                $description .= 'Title was changed from "'.$users->title.'" to "'.$entity->title.'".<br/>';
            }
            if($entity->first_name != $users->first_name){
                $description .= 'First Name was changed from "'.$users->first_name.'" to "'.$entity->first_name.'".<br/>';
            }
            if($entity->middle_name != $users->middle_name){
                $description .= 'Middle Name was changed from  "'.$users->middle_name.'" to "'.$entity->middle_name.'".<br/>';;
            }
            if($entity->last_name != $users->last_name){
                $description .= 'Last Name was changed from  "'.$users->last_name.'" to "'.$entity->last_name.'".<br/>';;
            }
            if($entity->suffix != $users->suffix){
                $description .= 'Suffix was changed from "'.$users->suffix.'" to "'.$entity->suffix.'".<br/>';
            }
            if($entity->email != $users->email){
                $description .= 'Email was changed from "'.$users->email.'" to "'.$entity->email.'".<br/>';
            }
            if($entity->phone_ext != $users->phone_ext){
                $description .= 'Phone Ext was changed from "'.$users->phone_ext.'" to "'.$entity->phone_ext.'".<br/>';
            }
            if($entity->phone != $users->phone){
                $description .= 'Mobile Number was changed from "'.$users->phone.'" to "'.$entity->phone.'".<br/>';
            }
            if($entity->home_phone != $users->home_phone){
                $description .= 'Office/Home Phone Number was changed from "'.$users->home_phone.'" to "'.$entity->home_phone.'".<br/>';
            }
            if($entity->role_id != $users->role_id){
                $description .= 'Role was changed from "'.$users->role_id.'" to "'.$entity->role_id.'".<br/>';
            }
            if($entity->is_manager != $users->is_manager){
                $description .= 'Is Manager was changed from "'.$users->is_manager.'" to "'.$entity->is_manager.'".<br/>';
            }
            if($entity->team_member_id != $users->team_member_id){
                $description .= 'Select your team members was changed from "'.$users->team_member_id.'" to "'.$entity->team_member_id.'".<br/>';
            }
            if($entity->direct_manager_id != $users->direct_manager_id){
                $description .= 'Direct Manager was changed from "'.$users->direct_manager_id.'" to "'.$entity->direct_manager_id.'".<br/>';
            }
            if($entity->employment_date != $users->employment_date){
                $description .= 'Employment Date was changed from "'.$users->employment_date.'" to "'.$entity->employment_date.'".<br/>';
            }
            if($entity->salary != $users->salary){
                $description .= 'Salary was changed from "'.$users->salary.'" to "'.$entity->salary.'".<br/>';
            }
            if($entity->department_id != $users->department_id){
                $description .= 'Department/Job Title was changed from "'.$users->department_id.'" to "'.$entity->department_id.'".<br/>';
            }
            if($entity->pto_accrual_rate != $users->pto_accrual_rate){
                $description .= 'PTO Accrual Rate was changed from "'.$users->pto_accrual_rate.'" to "'.$entity->pto_accrual_rate.'".<br/>';
            }
            if($entity->first_paycheck_date != $users->first_paycheck_date){
                $description .= 'First Paycheck Date was changed from "'.$users->first_paycheck_date.'" to "'.$entity->first_paycheck_date.'".<br/>';
            }
            if($entity->suspended != $users->suspended){
                $description .= 'Suspend Account was changed from "'.$users->suspended.'" to "'.$entity->suspended.'".<br/>';
            }
            if($entity->welcome_email != $users->welcome_email){
                $description .= 'Send Welcome Email was changed from "'.$users->welcome_email.'" to "'.$entity->welcome_email.'".<br/>';
            }

            if (!empty($entity->addresses) && isset($entity->addresses[0])) {
                $addressarr = $entity->addresses[0];

                if($addressarr->address_line1 != $addresses->address_line1){
                    $description .= 'Address1 was changed from "'.$addresses->address_line1.'" to "'.$addressarr->address_line1.'".<br/>';
                }
                if($addressarr->address_line2 != $addresses->address_line2){
                    $description .= 'Address2 was changed from "'.$addresses->address_line2.'" to "'.$addressarr->address_line2.'".<br/>';
                }
                if($addressarr->country_id != $addresses->country_id){
                    $description .= 'Country was changed from "'.$addresses->country_id.'" to "'.$addressarr->country_id.'".<br/>';
                }
                if($addressarr->state_id != $addresses->state_id){
                    $description .= 'State was changed from "'.$addresses->state_id.'" to "'.$addressarr->state_id.'".<br/>';
                }
                if($addressarr->city_id != $addresses->city_id){
                    $description .= 'City was changed from "'.$addresses->city_id.'" to "'.$addressarr->city_id.'".<br/>';
                }
                if($addressarr->zip_code != $addresses->zip_code){
                    $description .= 'Zip code was changed from "'.$addresses->zip_code.'" to "'.$addressarr->zip_code.'".<br/>';
                }
            }

            if(!empty($description)){
                $description .= 'Last updated was changed from "'.$modified_from.'" to "'.$modified_to.'".<br/>';
            
                $userHistory->user_id = $entity->updated_by;
                $userHistory->description = $description;
                $userHistoriesModel->save($userHistory);
            }
        }

        return true;
    }
}
