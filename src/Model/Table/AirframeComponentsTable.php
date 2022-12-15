<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
//use SoftDelete\Model\Table\SoftDeleteTrait;

/**
 * AirframeComponents Model
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
class AirframeComponentsTable extends Table
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
        $this->setTable('airframe_components');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');

        $this->belongsTo('Planes', [
            'foreignKey' => 'plane_id',
            'joinType' => 'INNER'
        ]);

        $this->hasMany('AirframeComponentCategories', [
            'foreignKey' => 'airframe_component_id'
        ]);

        $this->hasMany('AirframeComponentTimes', [
            'foreignKey' => 'airframe_component_id'
        ]);

        $this->hasMany('SubComponents', [
            'foreignKey' => 'airframe_component_id'
        ]);

        $this->hasMany('AirframeComponentParts', [
            'foreignKey' => 'airframe_component_id'
        ]);

        $this->hasMany('AirframeComponentLastCW', [
            'foreignKey' => 'airframe_component_id'
        ]);

        $this->hasMany('AddDiscrepancies', [
            'foreignKey' => 'airframe_component_id'
        ]);

        $this->hasMany('PartInstalledTimes', [
            'foreignKey' => 'airframe_component_id'
        ]);

        $this->hasMany('Utilizations', [
            'foreignKey' => 'airframe_component_id'
        ]);

        $this->hasMany('Groups', [
            'foreignKey' => 'airframe_component_id'
        ]);

        /*$this->hasMany('ExtraDetails', [
            'foreignKey' => 'airframe_component_id'
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
            ->integer('plane_id')
            ->requirePresence('plane_id', 'create')
            ->notEmpty('plane_id');
        
        $validator
            ->scalar('log_book')
            ->maxLength('log_book', 100)
            ->notEmpty('log_book');

        $validator
            ->integer('position')
            ->allowEmpty('position');

        return $validator;
    }
    
}
