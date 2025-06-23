<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Datasource\FactoryLocator;

/**
 * Groups Model
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
class GroupsTable extends Table
{
    //use SoftDeleteTrait;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config):void
    {
        parent::initialize($config);
        $this->setTable('groups');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');

        $this->belongsTo('Planes', [
            'foreignKey' => 'plane_id',
            'joinType' => 'INNER'
        ]);

        $this->belongsTo('AirframeComponents', [
            'foreignKey' => 'airframe_component_id',
            'joinType' => 'INNER'
        ]);

        $this->belongsTo('AirframeComponentParts', [
            'foreignKey' => 'airframe_component_part_id',
            'joinType' => 'INNER'
        ]);

        /*$this->belongsTo('AirframeComponentParts', [
            'foreignKey' => 'airframe_component_part_id',
            'joinType' => 'INNER'
        ]);*/

        $this->hasMany('ParentChildRelations', [
            'foreignKey' => 'airframe_component_part_id'
        ]);
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
            ->integer('plane_id')
            ->requirePresence('plane_id', 'create')
            ->notEmptyString('plane_id');

        $validator
            ->integer('airframe_component_id')
            ->requirePresence('airframe_component_id', 'create')
            ->notEmptyString('airframe_component_id');

        $validator
            ->integer('airframe_component_part_id')
            ->requirePresence('airframe_component_part_id', 'create')
            ->notEmptyString('airframe_component_part_id');

        $validator
            ->scalar('group_name')
            ->maxLength('group_name', 50)
            ->allowEmptyString('group_name');

        return $validator;
    }
    
}
