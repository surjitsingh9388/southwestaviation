<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
//use SoftDelete\Model\Table\SoftDeleteTrait;

/**
 * AirframeComponentParts Model
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
class AirframeComponentPartsTable extends Table
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
        $this->setTable('airframe_component_parts');
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
            'joinType' => 'LEFT'
        ]);

        $this->hasMany('AirframeComponentLastCw', [
            'foreignKey' => 'airframe_component_part_id'
        ]);

        $this->hasMany('PartInstalledTimes', [
            'foreignKey' => 'airframe_component_part_id'
        ]);

        $this->hasOne('Groups', [
            'foreignKey' => 'airframe_component_part_id'
        ]);

        $this->hasOne('ParentChildRelations', [
            'foreignKey' => 'airframe_component_part_id'
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
            ->integer('airframe_category_id')
            ->allowEmpty('airframe_category_id');

        $validator
            ->scalar('ata_code')
            ->maxLength('ata_code', 50)
            ->allowEmpty('ata_code');

        $validator
            ->scalar('mfg_code')
            ->maxLength('mfg_code', 50)
            ->allowEmpty('mfg_code');

        $validator
            ->scalar('item_type')
            ->maxLength('item_type', 50)
            ->allowEmpty('item_type');

        $validator
            ->scalar('ad_sb_number')
            ->maxLength('ad_sb_number', 50)
            ->allowEmpty('ad_sb_number');

        $validator
            ->scalar('ad_sb_status')
            ->maxLength('ad_sb_status', 50)
            ->allowEmpty('ad_sb_status');

        $validator
            ->scalar('amendment')
            ->maxLength('amendment', 50)
            ->allowEmpty('amendment');

        $validator
            ->scalar('authority')
            ->maxLength('authority', 50)
            ->allowEmpty('authority');

        $validator
            ->scalar('description')
            ->allowEmpty('description');

        $validator
            ->scalar('notes')
            ->allowEmpty('notes');

        $validator
            ->scalar('disposition')
            ->allowEmpty('disposition');

        $validator
            ->scalar('reference')
            ->allowEmpty('reference');

        $validator
            ->scalar('requirement_type')
            ->allowEmpty('requirement_type');

        $validator
            ->scalar('tags')
            ->allowEmpty('tags');

        $validator
            ->scalar('part_number')
            ->maxLength('part_number', 100)
            ->allowEmpty('part_number');

        $validator
            ->scalar('serial_number')
            ->maxLength('serial_number', 100)
            ->allowEmpty('serial_number');

        $validator
            ->scalar('installed_status')
            ->maxLength('installed_status', 50)
            ->allowEmpty('installed_status');

        $validator
            ->scalar('work_description')
            ->allowEmpty('work_description');

        $validator
            ->scalar('discrepancy_status')
            ->allowEmpty('discrepancy_status');

        $validator
            ->scalar('hardware')
            ->maxLength('hardware', 100)
            ->allowEmpty('hardware');
        
        $validator
            ->scalar('software')
            ->maxLength('software', 100)
            ->allowEmpty('software');

        $validator
            ->scalar('mm_ref')
            ->maxLength('mm_ref', 200)
            ->allowEmpty('mm_ref');

        $validator
            ->scalar('ops_numbers')
            ->maxLength('ops_numbers', 200)
            ->allowEmpty('ops_numbers');

        $validator
            ->decimal('avg_man_hrs')
            ->allowEmpty('avg_man_hrs');

        $validator
            ->decimal('approx_price')
            ->allowEmpty('approx_price');

        $validator
            ->scalar('manufacturer')
            ->allowEmpty('manufacturer');

        $validator
            ->scalar('admin_notes')
            ->allowEmpty('admin_notes');

        $validator
            ->scalar('work_card')
            ->maxLength('work_card', 50)
            ->allowEmpty('work_card');

        $validator
            ->scalar('position')
            ->maxLength('position', 50)
            ->allowEmpty('position');

        $validator
            ->scalar('revision')
            ->maxLength('revision', 50)
            ->allowEmpty('revision');

        $validator
            ->scalar('version')
            ->maxLength('version', 50)
            ->allowEmpty('version');

        $validator
            ->integer('parent_id')
            ->allowEmpty('parent_id');

        $validator
            ->integer('clone_id')
            ->allowEmpty('clone_id');

        $validator
            ->scalar('quick_ref')
            ->maxLength('quick_ref', 20)
            ->allowEmpty('quick_ref');

        return $validator;
    }

}
