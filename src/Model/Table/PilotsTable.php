<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;
use Cake\Http\Session;
use Cake\Datasource\FactoryLocator;

/**
 * Pilots Model
 *
 * @property \App\Model\Table\DutyAssignmentsTable|\Cake\ORM\Association\HasMany $DutyAssignments
 *
 * @method \App\Model\Entity\Routes get($primaryKey, $options = [])
 * @method \App\Model\Entity\Routes newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Routes[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Routes|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Routes patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Routes[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Routes findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PilotsTable extends Table
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
        $this->setTable('pilots');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');

        $this->hasMany('DutyAssignments', [
            'foreignKey' => 'pilot_id',
            'dependent' => true
        ]);

        $this->hasMany('PilotCertificates', [
            'foreignKey' => 'pilot_id',
            'dependent' => true
        ]);

        $this->hasMany('PilotCheckings', [
            'foreignKey' => 'pilot_id',
            'dependent' => true
        ]);

        $this->hasMany('PilotTrainings', [
            'foreignKey' => 'pilot_id',
            'dependent' => true
        ]);

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id'
        ]);

        $this->hasMany('DutyTimes', [
            'foreignKey' => 'pilot_id',
            'dependent' => true
        ]);

        $this->hasMany('DaysOff', [
            'foreignKey' => 'pilot_id',
            'dependent' => true
        ]);

        $this->hasMany('FlightLegDetails', [
            'foreignKey' => 'pilot_id',
            'dependent' => true
        ]);

        $this->hasMany('Documents', [
            'foreignKey' => 'pilot_id',
            'dependent' => true
        ]);

        /*$this->hasMany('FlightLogs', [
            'foreignKey' => 'pilot_id',
            'dependent' => true
        ]);*/

        /*$this->hasMany('CrewDetails', [
            'foreignKey' => 'pilot_id',
            'dependent' => true
        ]);*/
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator):Validator
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', 'create');

        $validator
            ->integer('user_id')
            ->allowEmptyString('user_id');
        
        $validator
            ->scalar('certificate_number')
            ->maxLength('certificate_number', 50)
            ->requirePresence('certificate_number', 'create')
            ->notEmptyString('certificate_number');
        
        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    /*public function buildRules(RulesChecker $rules):RulesChecker
    {
        $rules->add($rules->isUnique(['email']));
        return $rules;
    }*/

    //Before save change date format
    public function beforeSave($options = array())
    {
        $entity = $options->getData('entity');
        
        if(!empty($entity->id)){
            $pilotsModel =  FactoryLocator::get('Table')->get('Pilots');
            $pilots = $pilotsModel->get($entity->id);
            $pilotHistoriesModel =  FactoryLocator::get('Table')->get('PilotHistories');
            
            $pilotHistory = $pilotHistoriesModel->newEmptyEntity();
            $pilotHistory->pilot_id = $entity->id;

            $pilotname = $entity->first_name;
            $pilotname .= !empty($entity->middle_name) ? ' '.$entity->middle_name : '';
            $pilotname .= ' '.$entity->last_name;
            
            $pilotHistory->title = 'Pilot `'.$pilotname.'` was updated.';
            
            if(!empty($pilots->modified)){
                $modified_from = str_replace('-', '/', $pilots->modified);
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
            if($entity->user_id != $pilots->user_id){
                $description .= 'Pilot was changed from "'.$pilots->user_id.'" to "'.$entity->user_id.'".<br/>';
            }
            if($entity->certificate_number != $pilots->certificate_number){
                $description .= 'Certificate Number was changed from "'.$pilots->certificate_number.'" to "'.$entity->certificate_number.'".<br/>';
            }
            if($entity->phone != $pilots->phone){
                $description .= 'Primary Phone was changed from "'.$pilots->phone.'" to "'.$entity->phone.'".<br/>';
            }
            if($entity->secondary_phone != $pilots->secondary_phone){
                $description .= 'Secondary Phone was changed from "'.$pilots->secondary_phone.'" to "'.$entity->secondary_phone.'".<br/>';
            }
            if($entity->emergency_contact != $pilots->emergency_contact){
                $description .= 'Emergency Contact was changed from "'.$pilots->emergency_contact.'" to "'.$entity->emergency_contact.'".<br/>';
            }
            if($entity->emergency_phone != $pilots->emergency_phone){
                $description .= 'Emergency Phone was changed from "'.$pilots->emergency_phone.'" to "'.$entity->emergency_phone.'".<br/>';
            }
            if($entity->status != $pilots->status){
                $description .= 'Status was changed from "'.$pilots->status.'" to "'.$entity->status.'".<br/>';
            }

            if(!empty($description)){
                $description .= 'Last updated was changed from "'.$modified_from.'" to "'.$modified_to.'".<br/>';
            }
            
            $session = new Session();
            $sessionUser = $session->read('Auth');
            $pilotHistory->user_id = $sessionUser['id'];
            $pilotHistory->description = $description;
            if(!empty($description)){
                $pilotHistoriesModel->save($pilotHistory);
            }
        }

        return true;
    }
}
