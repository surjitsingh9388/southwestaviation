<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

/**
 * AirframeComponentLastCw Model
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
class AirframeComponentLastCwTable extends Table
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
        $this->setTable('airframe_component_last_cw');
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

        $this->belongsTo('AirframeCategories', [
            'foreignKey' => 'airframe_category_id',
            'joinType' => 'INNER'
        ]);

        $this->belongsTo('AirframeComponentParts', [
            'foreignKey' => 'airframe_component_part_id',
            'joinType' => 'INNER'
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
            ->integer('airframe_component_id')
            ->notEmpty('airframe_component_id');

        $validator
            ->integer('airframe_category_id')
            ->allowEmpty('airframe_category_id');

        $validator
            ->integer('airframe_component_part_id')
            ->notEmpty('airframe_component_part_id');
        
        /*$validator
            ->date('last_cw_date')
            ->allowEmpty('last_cw_date');

        $validator
            ->decimal('last_cw_hrs')
            ->allowEmpty('last_cw_hrs');

        $validator
            ->integer('last_cw_afl')
            ->allowEmpty('last_cw_afl');

        $validator
            ->integer('last_cw_msc')
            ->allowEmpty('last_cw_msc');
        
        $validator
            ->date('next_due_date')
            ->allowEmpty('next_due_date');

        $validator
            ->decimal('next_due_hrs')
            ->allowEmpty('next_due_hrs');

        $validator
            ->integer('next_due_afl')
            ->allowEmpty('next_due_afl');

        $validator
            ->integer('next_due_msc')
            ->allowEmpty('next_due_msc');

        $validator
            ->decimal('tolerance_mos')
            ->allowEmpty('tolerance_mos');

        $validator
            ->integer('tolerance_days')
            ->allowEmpty('tolerance_days');

        $validator
            ->decimal('tolerance_hrs')
            ->allowEmpty('tolerance_hrs');

        $validator
            ->integer('tolerance_afl')
            ->allowEmpty('tolerance_afl');

        $validator
            ->integer('alert_days')
            ->allowEmpty('alert_days');

        $validator
            ->decimal('alert_hrs')
            ->allowEmpty('alert_hrs');

        $validator
            ->integer('alert_afl')
            ->allowEmpty('alert_afl');

        $validator
            ->decimal('recurring_mos')
            ->allowEmpty('recurring_mos');

        $validator
            ->integer('recurring_days')
            ->allowEmpty('recurring_days');

        $validator
            ->decimal('recurring_hrs')
            ->allowEmpty('recurring_hrs');

        $validator
            ->integer('recurring_afl')
            ->allowEmpty('recurring_afl');

        $validator
            ->decimal('threshold_mos')
            ->allowEmpty('threshold_mos');

        $validator
            ->integer('threshold_days')
            ->allowEmpty('threshold_days');

        $validator
            ->decimal('threshold_hrs')
            ->allowEmpty('threshold_hrs');

        $validator
            ->integer('threshold_afl')
            ->allowEmpty('threshold_afl');

        $validator
            ->decimal('required_frequency_mos')
            ->allowEmpty('required_frequency_mos');

        $validator
            ->integer('required_frequency_days')
            ->allowEmpty('required_frequency_days');

        $validator
            ->decimal('required_frequency_hrs')
            ->allowEmpty('required_frequency_hrs');

        $validator
            ->integer('required_frequency_afl')
            ->allowEmpty('required_frequency_afl');

        $validator
            ->decimal('adjustment_mos')
            ->allowEmpty('adjustment_mos');

        $validator
            ->integer('adjustment_days')
            ->allowEmpty('adjustment_days');

        $validator
            ->decimal('adjustment_hrs')
            ->allowEmpty('adjustment_hrs');

        $validator
            ->integer('adjustment_afl')
            ->allowEmpty('adjustment_afl');

        $validator
            ->scalar('last_revised_by')
            ->maxLength('last_revised_by', 100)
            ->allowEmpty('last_revised_by');

        $validator
            ->scalar('last_reported_by')
            ->maxLength('last_reported_by', 100)
            ->allowEmpty('last_reported_by');

        $validator
            ->scalar('is_recThres')
            ->maxLength('is_recThres', 50)
            ->allowEmpty('is_recThres');*/

        return $validator;
    }

    //Before save change date format
    public function beforeSave($options = array())
    {
        $entity = $options->getData('entity');

        if(!empty($entity->last_cw_date)) {
            $entity->last_cw_date = $this->Planes->dateFormatBeforeSave($entity->last_cw_date);
        }

        if(!empty($entity->next_due_date)) {
            $entity->next_due_date = $this->Planes->dateFormatBeforeSave($entity->next_due_date);
        }

        return true;
    }
    
}
