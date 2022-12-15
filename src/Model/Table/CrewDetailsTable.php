<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

/**
 * CrewDetails Model
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
class CrewDetailsTable extends Table
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
        $this->setTable('crew_details');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');

        $this->belongsTo('Planes', [
            'foreignKey' => 'plane_id'
        ]);

        $this->belongsTo('Flightlogs', [
            'foreignKey' => 'flightlog_id'
        ]);

        $this->Planes = TableRegistry::get('Planes');
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
            ->integer('plane_id')
            ->notEmpty('plane_id');

        $validator
            ->integer('flightlog_id')
            ->notEmpty('flightlog_id');

        return $validator;
    }

    //Before save change date format
    public function beforeSave($options = array())
    {
        $entity = $options->getData('entity');
        if (!empty($entity->duty_start_date)) {
            $entity->duty_start_date = $this->Planes->dateFormatBeforeSave($entity->duty_start_date);
        }

        if (!empty($entity->duty_on)) {
            $entity->duty_on = $this->Planes->dateFormatBeforeSave($entity->duty_on);
        }

        if (!empty($entity->duty_off)) {
            $entity->duty_off = $this->Planes->dateFormatBeforeSave($entity->duty_off);
        }
        return true;
    }
}
