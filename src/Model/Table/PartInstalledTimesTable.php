<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
//use SoftDelete\Model\Table\SoftDeleteTrait;

/**
 * PartInstalledTimes Model
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
class PartInstalledTimesTable extends Table
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
        $this->setTable('part_installed_times');
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
            ->integer('airframe_component_id')
            ->notEmpty('airframe_component_id');

        $validator
            ->integer('airframe_component_part_id')
            ->notEmpty('airframe_component_part_id');
        
        $validator
            ->scalar('removed_part_number')
            ->maxLength('removed_part_number', 100)
            ->allowEmpty('removed_part_number');

        $validator
            ->scalar('removed_serial_number')
            ->maxLength('removed_serial_number', 100)
            ->allowEmpty('removed_serial_number');

        $validator
            ->scalar('removal_reason')
            ->maxLength('removal_reason', 50)
            ->allowEmpty('removal_reason');

        $validator
            ->scalar('new_months')
            ->allowEmpty('new_months');

        $validator
            ->decimal('new_hours')
            ->allowEmpty('new_hours');

        $validator
            ->integer('new_landings')
            ->allowEmpty('new_landings');

        $validator
            ->scalar('overhaul_months')
            ->allowEmpty('overhaul_months');

        $validator
            ->decimal('overhaul_hours')
            ->allowEmpty('overhaul_hours');

        $validator
            ->integer('overhaul_landings')
            ->allowEmpty('overhaul_landings');

        $validator
            ->scalar('repair_months')
            ->allowEmpty('repair_months');

        $validator
            ->decimal('repair_hours')
            ->allowEmpty('repair_hours');

        $validator
            ->integer('repair_landings')
            ->allowEmpty('repair_landings');

        $validator
            ->scalar('part_type')
            ->maxLength('part_type', 20)
            ->allowEmpty('part_type');

        return $validator;
    }
    
}
