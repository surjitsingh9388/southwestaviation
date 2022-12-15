<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Planes Model
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
class PlanesTable extends Table
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
        $this->setTable('planes');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');        
        
        $this->hasMany('AirframeComponents', [
            'foreignKey' => 'plane_id'
        ]);

        $this->hasMany('AirframeCategories', [
            'foreignKey' => 'plane_id'
        ]);

        $this->hasMany('AirframeComponentTimes', [
            'foreignKey' => 'plane_id'
        ]);

        $this->hasMany('AirframeComponentParts', [
            'foreignKey' => 'plane_id'
        ]);

        $this->hasMany('AirframeComponentLastCW', [
            'foreignKey' => 'plane_id'
        ]);

        $this->hasMany('AddDiscrepancies', [
            'foreignKey' => 'plane_id'
        ]);

        $this->hasMany('ExtraDetails', [
            'foreignKey' => 'plane_id'
        ]);

        $this->hasMany('PartInstalledTimes', [
            'foreignKey' => 'plane_id'
        ]);

        $this->hasMany('Utilizations', [
            'foreignKey' => 'plane_id'
        ]);

        $this->hasMany('Groups', [
            'foreignKey' => 'plane_id'
        ]);

        $this->hasMany('Flightlogs', [
            'foreignKey' => 'plane_id',
            'dependent' => true
        ]);

        /*$this->hasMany('CrewDetails', [
            'foreignKey' => 'plane_id',
            'dependent' => true
        ]);*/

        $this->hasMany('Crews', [
            'foreignKey' => 'plane_id',
            'dependent' => true
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
            ->scalar('plane_name')
            ->maxLength('plane_name', 100)
            ->allowEmpty('plane_name');
        
        $validator
            ->scalar('plane_type')
            ->maxLength('plane_type', 100)
            ->allowEmpty('plane_type');

        $validator
            ->scalar('plane_code')
            ->maxLength('plane_code', 40)
            ->requirePresence('plane_code', 'create')
            ->notEmpty('plane_code');
        
        $validator
            ->scalar('manufactured_by')
            ->maxLength('manufactured_by', 200)
            ->allowEmpty('manufactured_by');
        
        $validator
            ->scalar('plane_serial_number')
            ->maxLength('plane_serial_number', 100)
            ->allowEmpty('plane_serial_number');

        $validator
            ->scalar('federal_aviation_regulation')
            ->maxLength('federal_aviation_regulation', 100)
            ->allowEmpty('federal_aviation_regulation');

        $validator
            ->scalar('address')
            ->allowEmpty('address');

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
        $rules->add($rules->isUnique(['plane_code']));
        return $rules;
    }

    //Before save change date format
    public function beforeSave($options = array())
    {
        $entity = $options->getData('entity');

        if (!empty($entity->airworthiness_date)) {
            $entity->airworthiness_date = $this->dateFormatBeforeSave($entity->airworthiness_date);
        }

        if (!empty($entity->manufacturered_on)) {
            $entity->manufacturered_on = $this->dateFormatBeforeSave($entity->manufacturered_on);
        }
        
        return true;
    }

    //Change date format 'm-d-Y' to 'Y-m-d'
    public function dateFormatBeforeSave($dateString) 
    {
        $dateString = str_replace('-', '/', $dateString);
        return date('Y-m-d H:i:s', strtotime($dateString));
    }

    public function timeFormatBeforeSave($dateString) 
    {
        $dateString = str_replace('-', '/', $dateString);
        return date('H:i', strtotime($dateString));
    }
}
