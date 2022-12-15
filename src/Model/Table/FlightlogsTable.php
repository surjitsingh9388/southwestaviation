<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

/**
 * Flightlogs Model
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
class FlightlogsTable extends Table
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
        $this->setTable('flightlogs');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');

        $this->belongsTo('Planes', [
            'foreignKey' => 'plane_id'
        ]);

        $this->hasOne('FlightlogDetails', [
            'foreignKey' => 'flightlog_id',
            'dependent' => true
        ]);

        /*$this->hasMany('CrewDetails', [
            'foreignKey' => 'flightlog_id',
            'dependent' => true
        ]);*/

        $this->hasMany('Crews', [
            'foreignKey' => 'flightlog_id',
            'dependent' => true
        ]);

        $this->hasOne('Manifest', [
            'foreignKey' => 'flightlog_id',
            'dependent' => true
        ]);

        $this->hasMany('AirframeComponentTimes', [
            'foreignKey' => 'flightlog_id',
            'dependent' => true
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
            ->integer('pilot_id')
            ->allowEmpty('pilot_id');
        
        return $validator;
    }

    //Before save change date format
    public function beforeSave($options = array())
    {
        $entity = $options->getData('entity');
        if (!empty($entity->leg_date)) {
            $entity->leg_date = $this->Planes->dateFormatBeforeSave($entity->leg_date);
        }        
        return true;
    }

    //Save Flight Leg data
    public function saveFlightLegData($postData)
    {
        if(!empty($postData)) {
            if (!empty($postData['id'])) {
                $flightLeg = $this->get($postData['id']);
            } else {
                $flightLeg = $this->newEntity();
            }
            $flightLeg = $this->patchEntity($flightLeg, $postData);
            if($this->save($flightLeg)) {
                return true;
            }
            return false;
        }
    }

}
