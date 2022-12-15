<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

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
    public function initialize(array $config)
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
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->integer('user_id')
            ->allowEmpty('user_id');
        
        $validator
            ->scalar('certificate_number')
            ->maxLength('certificate_number', 50)
            ->requirePresence('certificate_number', 'create')
            ->notEmpty('certificate_number');
        
        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    /*public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->isUnique(['email']));
        return $rules;
    }*/
}
